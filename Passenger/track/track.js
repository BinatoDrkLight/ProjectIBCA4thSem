(() => {
  // --- CONFIG ---
  const POLL_INTERVAL_MS = 5000;
  const DEFAULT_CENTER = [51.505, -0.09];
  const DEFAULT_ZOOM = 14;
  const DEMO_SPEED = 0.05; // increment per frame

  // --- LocalStorage handling ---
  const cameFromRoute = (document.referrer || '').includes('routeH.php');
  if (!cameFromRoute) localStorage.removeItem('busId');

  const getSelectedBusId = () => {
    const v = localStorage.getItem('busId');
    return v ? String(v) : null;
  };

  // --- Parse URL center/zoom ---
  const urlParams = new URLSearchParams(window.location.search);
  let initialCenter = DEFAULT_CENTER.slice();
  let initialZoom = DEFAULT_ZOOM;
  const centerParam = urlParams.get('center');
  const zoomParam = parseInt(urlParams.get('zoom'));
  if (centerParam) {
    const parts = centerParam.split(',').map(s => s.trim());
    if (parts.length === 2 && !isNaN(parseFloat(parts[0])) && !isNaN(parseFloat(parts[1]))) {
      initialCenter = [parseFloat(parts[0]), parseFloat(parts[1])];
    }
  }
  if (!isNaN(zoomParam)) initialZoom = zoomParam;

  // --- Initialize map ---
  const map = L.map("map").setView(initialCenter, initialZoom);
  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  // --- Global state ---
  let userMarker = null;
  const busMarkers = {};
  let followUser = true;
  let distanceActive = false;
  let initViewDone = false;
  let lastSelectedBusId = null;

  // --- Routing control ---
  const routeControl = L.Routing.control({
    waypoints: [],
    lineOptions: { styles: [{ color: '#6A46B0', opacity: 0.8, weight: 3 }] },
    addWaypoints: false,
    draggableWaypoints: false,
    fitSelectedRoutes: false,
    show: false,
    showAlternatives: false,
    createMarker: () => null
  }).addTo(map);
  routeControl.getContainer().style.display = 'none';

  // --- Helpers ---
  const isValidLatLng = (lat, lng) => typeof lat === 'number' && !isNaN(lat);
  const round = n => Math.round(n * 1e6) / 1e6;

  const updateUserMarker = (lat, lng) => {
    if (!isValidLatLng(lat, lng)) return;
    if (userMarker) userMarker.setLatLng([lat, lng]);
    else {
      userMarker = L.marker([lat, lng], {
        icon: L.divIcon({
          html: '<i class="fa-solid fa-location-dot" style="color: #7A55D4; font-size: 24px;"></i>',
          iconSize: [24, 24],
          iconAnchor: [12, 12],
          className: ''
        })
      }).addTo(map);
    }
  };

  const createOrUpdateBusMarker = (idStr, lat, lng) => {
    if (!isValidLatLng(lat, lng)) return;
    if (!busMarkers[idStr]) {
      busMarkers[idStr] = L.marker([lat, lng], {
        icon: L.divIcon({
          html: '<i class="fas fa-bus" style="font-size:25px;color: #5A00A3"></i>',
          iconSize: [25, 25],
          iconAnchor: [12, 12],
          className: ''
        })
      }).addTo(map).bindPopup(`Bus ${idStr}`);
    } else {
      busMarkers[idStr].setLatLng([lat, lng]);
    }
  };

  const removeBusMarker = idStr => {
    const m = busMarkers[idStr];
    if (m) {
      if (map.hasLayer(m)) map.removeLayer(m);
      delete busMarkers[idStr];
    }
  };

  const removeAllBusMarkers = () => Object.keys(busMarkers).forEach(removeBusMarker);

  const maybeAutoFitToSelected = (selectedId) => {
    if (!followUser || initViewDone) return;
    const m = selectedId && busMarkers[selectedId] ? busMarkers[selectedId] : null;
    if (m) {
      if (userMarker) map.fitBounds(L.latLngBounds([userMarker.getLatLng(), m.getLatLng()]), { padding: [50, 50] });
      else map.setView(m.getLatLng(), 15);
      initViewDone = true;
    }
  };

  const maybeAutoFitToAll = () => {
    if (!followUser || initViewDone) return;
    const keys = Object.keys(busMarkers);
    if (!keys.length) return;
    const bounds = L.latLngBounds(keys.map(k => busMarkers[k].getLatLng()));
    if (userMarker) bounds.extend(userMarker.getLatLng());
    if (bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50] });
    initViewDone = true;
  };

  const showDistanceRoute = () => {
    if (!userMarker || !Object.keys(busMarkers).length) {
      routeControl.getContainer().style.display = 'none';
      return;
    }
    const selectedBusId = getSelectedBusId();
    let targetMarker = selectedBusId && busMarkers[selectedBusId] ? busMarkers[selectedBusId] : busMarkers[Object.keys(busMarkers)[0]];
    if (!targetMarker) return;
    routeControl.setWaypoints([userMarker.getLatLng(), targetMarker.getLatLng()]);
    routeControl.getContainer().style.display = 'block';
  };

  // --- Demo Bus Controller ---
  class DemoBusController {
    constructor(onStopCallback) {
      this.running = false;
      this.busId = null;
      this.pathCoords = [];
      this.index = 0;
      this.marker = null;
      this.frame = null;
      this.originalLatLng = null;
      this.onStopCallback = onStopCallback;
    }

    init(busId) {
      if (!busMarkers[busId] || !userMarker) return false;
      this.busId = busId;
      this.marker = busMarkers[busId];

      const routes = routeControl._routes;
      if (!routes || !routes.length || !routes[0].coordinates) return false;

      this.pathCoords = routes[0].coordinates.map(c => L.latLng(c.lat, c.lng));
      this.pathCoords.reverse(); // bus → user
      this.index = 0;
      this.originalLatLng = this.marker.getLatLng();
      return true;
    }

    start() {
      if (!this.pathCoords.length) return;
      this.running = true;

      const step = () => {
        if (!this.running) return;

        if (this.index >= this.pathCoords.length) {
          this.stop(true);
          return;
        }

        this.marker.setLatLng(this.pathCoords[Math.floor(this.index)]);
        this.index += DEMO_SPEED;
        this.frame = requestAnimationFrame(step);
      };

      step();
    }

    stop(resetMarker = false) {
      this.running = false;
      if (this.frame) cancelAnimationFrame(this.frame);
      this.frame = null;
      if (resetMarker && this.marker && this.originalLatLng) {
        this.marker.setLatLng(this.originalLatLng);
      }
      if (typeof this.onStopCallback === "function") {
        this.onStopCallback();
      }
    }

    restart(busId) {
      this.stop(true);
      if (!this.init(busId)) return false;
      this.start();
      return true;
    }
  }

  // --- Demo button ---
  let demoButtonIcon = null;
  let demoRunning = false;

  const demoBus = new DemoBusController(() => {
    if (demoButtonIcon) {
      demoButtonIcon.classList.remove("fa-stop");
      demoButtonIcon.classList.add("fa-play");
    }
    demoRunning = false;
  });

  // --- Helper to create buttons ---
  const createMapButton = (iconClass, bottomOffset, onClick) => {
    const control = L.control({ position: "bottomright" });
    control.onAdd = () => {
      const div = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-control-custom");
      div.style.marginBottom = bottomOffset + "px";
      div.style.border = "none";
      div.style.background = "transparent";
      div.style.overflow = "visible";
      const button = L.DomUtil.create("button", "", div);
      button.style.cssText = `width:2.4rem;height:2.4rem;padding:0;border:none;background: #B29CEE;cursor:pointer;
                              box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;`;
      button.innerHTML = `<i class="${iconClass}"></i>`;
      L.DomEvent.on(button, 'click', onClick);
      return div;
    };
    control.addTo(map);
  };

  // --- Add buttons in bottom → top order for correct stacking ---
  createMapButton("fas fa-user", 90, () => {
    if (!userMarker) return alert("User location not available!");
    followUser = true;
    initViewDone = false;
    map.flyTo(userMarker.getLatLng(), 15);
  });

  createMapButton("fas fa-bus", 10, () => {
    if(!cameFromRoute) return alert("Select a bus from route.");

    const selectedBusId = getSelectedBusId();
    const keys = Object.keys(busMarkers);
    if (!keys.length) return alert("No buses available!");
    let target = selectedBusId && busMarkers[selectedBusId] ? busMarkers[selectedBusId] : busMarkers[keys[0]];
    if (!target) return;
    followUser = true;
    initViewDone = false;
    map.flyTo(target.getLatLng(), 15);
  });

  createMapButton("fas fa-route", 10, () => {
    if (!cameFromRoute) return alert("Select a bus from route.");
    distanceActive = !distanceActive;
    if (distanceActive) showDistanceRoute();
    else routeControl.getContainer().style.display = 'none';
  });

  const demoButton = L.control({ position: "bottomright" });
  demoButton.onAdd = () => {
    const div = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-control-custom");
    div.style.border = "none";
    div.style.background = "transparent";
    div.style.overflow = "visible";

    const button = L.DomUtil.create("button", "", div);
    button.style.cssText = `width:2.4rem;height:2.4rem;padding:0;border:none;background: #B29CEE;cursor:pointer;
                            box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;`;
    demoButtonIcon = L.DomUtil.create("i", "fas fa-play", button);

    L.DomEvent.on(button, 'click', () => {
      const selectedBusId = getSelectedBusId();
      if (!selectedBusId) return alert("Select a bus for demo!");

      if (!demoRunning) {
        if (!demoBus.init(selectedBusId)) return alert("User location or route not ready yet!");
        demoBus.start();
        demoButtonIcon.classList.remove("fa-play");
        demoButtonIcon.classList.add("fa-stop");
        demoRunning = true;
      } else {
        demoBus.stop(true);
      }
    });

    return div;
  };
  demoButton.addTo(map);

  // --- Map events ---
  map.on('dragstart', () => followUser = false);
  map.on('zoomstart', () => followUser = false);
  map.on('moveend', () => {
    const center = map.getCenter();
    const zoom = map.getZoom();
    const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?center=${round(center.lat)},${round(center.lng)}&zoom=${zoom}`;
    window.history.replaceState({}, '', newUrl);
  });

  // --- Real-time user tracking ---
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
      pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        updateUserMarker(lat, lng);

        if (distanceActive) showDistanceRoute();
        if (followUser && !initViewDone) maybeAutoFitToAll();
      },
      err => console.warn("Geolocation error:", err),
      { enableHighAccuracy: true, maximumAge: 1000, timeout: 5000 }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
  }

  // --- Update buses ---
  const updateBuses = () => {
    const selectedBusId = getSelectedBusId();
    if (selectedBusId !== lastSelectedBusId) {
      initViewDone = false;
      lastSelectedBusId = selectedBusId;
      demoBus.stop(true);
    }

    $.ajax({
      url: "trackP.php",
      type: "POST",
      dataType: "json",
      success: res => {
        const busData = Array.isArray(res.busData) ? res.busData : [];

        if (selectedBusId) {
          const found = busData.find(b => {
            const idA = b.id !== undefined ? String(b.id) : null;
            const idB = b.l_id !== undefined ? String(b.l_id) : null;
            return idA === selectedBusId || idB === selectedBusId;
          });

          if (found) {
            const idStr = String(found.l_id ?? found.id ?? selectedBusId);
            const lat = parseFloat(found.latitude);
            const lng = parseFloat(found.longitude);

            Object.keys(busMarkers).forEach(id => { if (id !== idStr) removeBusMarker(id); });
            createOrUpdateBusMarker(idStr, lat, lng);
            maybeAutoFitToSelected(idStr);
          } else {
            removeAllBusMarkers();
          }
        } else {
          const seen = new Set();
          busData.forEach(bus => {
            const idStr = String(bus.id ?? bus.l_id ?? '');
            if (!idStr) return;
            const lat = parseFloat(bus.latitude);
            const lng = parseFloat(bus.longitude);
            if (!isValidLatLng(lat, lng)) return;
            seen.add(idStr);
            createOrUpdateBusMarker(idStr, lat, lng);
          });
          Object.keys(busMarkers).forEach(id => { if (!seen.has(id)) removeBusMarker(id); });
          maybeAutoFitToAll();
        }

        if (distanceActive) showDistanceRoute();
      },
      error: (xhr, status, err) => console.error("Error fetching bus data:", err)
    });
  };

  updateBuses();
  setInterval(updateBuses, POLL_INTERVAL_MS);

})();

(() => {
  // --- CONFIG ---
  const POLL_INTERVAL_MS = 5000;
  const DEFAULT_CENTER = [27.7172, 85.3240];
  const DEFAULT_ZOOM = 13;
  const DEMO_SPEED = 0.12; // increment per frame

  // --- LocalStorage & Route Origin Handling ---
  const urlParams = new URLSearchParams(window.location.search);
  const busIdParam = urlParams.get('bus_id');
  const cameFromRoute = (document.referrer || '').includes('routeH.php') || Boolean(busIdParam);
  if (!cameFromRoute) {
    localStorage.removeItem('busId');
  }

  const getSelectedBusId = () => {
    const fromUrl = new URLSearchParams(window.location.search).get('bus_id');
    const v = fromUrl || localStorage.getItem('busId');
    return v ? String(v) : null;
  };

  // --- Parse URL center/zoom ---
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
    lineOptions: { styles: [{ color: '#6A46B0', opacity: 0.8, weight: 4 }] },
    addWaypoints: false,
    draggableWaypoints: false,
    fitSelectedRoutes: false,
    show: false,
    showAlternatives: false,
    createMarker: () => null
  }).addTo(map);
  routeControl.getContainer().style.display = 'none';
  routeControl.on('routesfound', (e) => {
    if (distanceActive && e.routes && e.routes[0]) {
      const coords = e.routes[0].coordinates;
      if (coords && coords.length) {
        const bounds = L.latLngBounds(coords);
        if (userMarker) bounds.extend(userMarker.getLatLng());
        const target = getTargetBusMarker();
        if (target) bounds.extend(target.getLatLng());
        if (bounds.isValid()) {
          map.fitBounds(bounds, { padding: [60, 60] });
        }
      }
    }
  });

  // --- Helpers ---
  const isValidLatLng = (lat, lng) => typeof lat === 'number' && !isNaN(lat) && typeof lng === 'number' && !isNaN(lng);
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

  const createOrUpdateBusMarker = (idStr, lat, lng, busData = null) => {
    if (!isValidLatLng(lat, lng)) return;
    if (!busMarkers[idStr]) {
      const marker = L.marker([lat, lng], {
        icon: L.divIcon({
          html: '<i class="fas fa-bus" style="font-size:25px;color: #5A00A3"></i>',
          iconSize: [25, 25],
          iconAnchor: [12, 12],
          className: ''
        })
      }).addTo(map).bindPopup(`Bus ${idStr}`);
      marker._busData = busData;
      busMarkers[idStr] = marker;
    } else {
      busMarkers[idStr].setLatLng([lat, lng]);
      if (busData) busMarkers[idStr]._busData = busData;
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

  const getTargetBusMarker = () => {
    const selectedBusId = getSelectedBusId();
    if (selectedBusId && busMarkers[selectedBusId]) return busMarkers[selectedBusId];
    if (selectedBusId) {
      for (const k of Object.keys(busMarkers)) {
        const m = busMarkers[k];
        if (m && m._busData) {
          if (String(m._busData.id) === selectedBusId || String(m._busData.l_id) === selectedBusId) {
            return m;
          }
        }
      }
    }
    const keys = Object.keys(busMarkers);
    return keys.length ? busMarkers[keys[0]] : null;
  };

  const fitUserAndBus = () => {
    const target = getTargetBusMarker();
    if (userMarker && target) {
      const bounds = L.latLngBounds([userMarker.getLatLng(), target.getLatLng()]);
      if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [60, 60] });
      }
    } else if (target) {
      map.flyTo(target.getLatLng(), 15);
    } else if (userMarker) {
      map.flyTo(userMarker.getLatLng(), 15);
    }
  };

  const maybeAutoFitToSelected = (selectedId) => {
    if (!followUser || initViewDone) return;
    const m = getTargetBusMarker();
    if (m) {
      if (userMarker) {
        fitUserAndBus();
        initViewDone = true;
      } else {
        map.setView(m.getLatLng(), 15);
      }
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
      if (routeControl.getContainer()) routeControl.getContainer().style.display = 'none';
      return;
    }
    const targetMarker = getTargetBusMarker();
    if (!targetMarker) return;
    routeControl.setWaypoints([userMarker.getLatLng(), targetMarker.getLatLng()]);
    if (routeControl.getContainer()) routeControl.getContainer().style.display = 'none';
  };

  const hideDistanceRoute = () => {
    distanceActive = false;
    routeControl.setWaypoints([]);
    if (routeControl.getContainer()) routeControl.getContainer().style.display = 'none';
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

    initWithCoords(busId, marker, coords) {
      if (!marker || !coords || !coords.length) return false;
      this.busId = busId;
      this.marker = marker;
      this.originalLatLng = this.marker.getLatLng();
      this.pathCoords = coords;
      this.index = 0;
      return true;
    }

    start() {
      if (!this.pathCoords.length || !this.marker) return;
      this.running = true;

      const step = () => {
        if (!this.running) return;

        if (this.index >= this.pathCoords.length - 1) {
          this.marker.setLatLng(this.pathCoords[this.pathCoords.length - 1]);
          this.stop(true);
          return;
        }

        const idx = Math.floor(this.index);
        const nextIdx = Math.min(idx + 1, this.pathCoords.length - 1);
        const fraction = this.index - idx;
        const p1 = this.pathCoords[idx];
        const p2 = this.pathCoords[nextIdx];

        const curLat = p1.lat + (p2.lat - p1.lat) * fraction;
        const curLng = p1.lng + (p2.lng - p1.lng) * fraction;
        this.marker.setLatLng([curLat, curLng]);

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
  }

  // --- Demo Button & Route Follower ---
  let demoButtonIcon = null;
  let demoRunning = false;

  const resetDemoButtonIcon = () => {
    if (demoButtonIcon) {
      demoButtonIcon.className = "fas fa-play";
    }
    demoRunning = false;
  };

  const demoBus = new DemoBusController(() => {
    resetDemoButtonIcon();
  });

  const startDemoAlongRoad = (busId, targetMarker) => {
    let started = false;

    const startFromCoords = (coords) => {
      if (started) return;
      started = true;
      demoBus.initWithCoords(busId, targetMarker, coords);
      demoBus.start();
      if (demoButtonIcon) {
        demoButtonIcon.className = "fas fa-stop";
      }
      demoRunning = true;
    };

    // Check if routeControl already has cached road coordinates for this path
    const currentRoutes = routeControl._routes;
    if (currentRoutes && currentRoutes.length && currentRoutes[0].coordinates && currentRoutes[0].coordinates.length) {
      const coords = currentRoutes[0].coordinates.map(c => L.latLng(c.lat, c.lng));
      const busPos = targetMarker.getLatLng();
      const distStart = busPos.distanceTo(coords[0]);
      const distEnd = busPos.distanceTo(coords[coords.length - 1]);
      if (distStart > distEnd) {
        coords.reverse();
      }
      startFromCoords(coords);
      return;
    }

    // Otherwise show loading spinner while OSRM finishes computing the road path
    if (demoButtonIcon) {
      demoButtonIcon.className = "fas fa-spinner fa-spin";
    }

    const onRoutesFound = (e) => {
      routeControl.off('routesfound', onRoutesFound);
      routeControl.off('routingerror', onRoutingError);
      clearTimeout(timeoutId);

      const foundRoutes = e.routes;
      if (foundRoutes && foundRoutes.length && foundRoutes[0].coordinates && foundRoutes[0].coordinates.length) {
        const coords = foundRoutes[0].coordinates.map(c => L.latLng(c.lat, c.lng));
        const busPos = targetMarker.getLatLng();
        const distStart = busPos.distanceTo(coords[0]);
        const distEnd = busPos.distanceTo(coords[coords.length - 1]);
        if (distStart > distEnd) {
          coords.reverse();
        }
        startFromCoords(coords);
      } else {
        resetDemoButtonIcon();
        alert("No road path found.");
      }
    };

    const onRoutingError = () => {
      routeControl.off('routesfound', onRoutesFound);
      routeControl.off('routingerror', onRoutingError);
      clearTimeout(timeoutId);
      resetDemoButtonIcon();
      alert("Unable to calculate road path. Please try again.");
    };

    const timeoutId = setTimeout(() => {
      routeControl.off('routesfound', onRoutesFound);
      routeControl.off('routingerror', onRoutingError);
      if (!started) {
        resetDemoButtonIcon();
        alert("Road path request timed out. Please try again.");
      }
    }, 10000);

    routeControl.once('routesfound', onRoutesFound);
    routeControl.once('routingerror', onRoutingError);
  };

  // --- Helper to create buttons ---
  const createMapButton = (iconClass, bottomOffset, onClick) => {
    const control = L.control({ position: "bottomright" });
    control.onAdd = () => {
      const div = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-control-custom");
      div.style.marginBottom = bottomOffset + "px";
      div.style.border = "none";
      div.style.background = "transparent";
      div.style.overflow = "visible";
      L.DomEvent.disableClickPropagation(div);
      const button = L.DomUtil.create("button", "", div);
      button.style.cssText = `width:2.4rem;height:2.4rem;padding:0;border:none;background: #B29CEE;cursor:pointer;
                              box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;`;
      button.innerHTML = `<i class="${iconClass}"></i>`;
      L.DomEvent.on(button, 'click', (e) => {
        L.DomEvent.stopPropagation(e);
        onClick();
      });
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
    if (!cameFromRoute) return alert("Select a bus from route.");
    const target = getTargetBusMarker();
    if (!target) return alert("No buses available!");
    followUser = true;
    initViewDone = false;
    map.flyTo(target.getLatLng(), 15);
  });

  createMapButton("fas fa-route", 10, () => {
    if (!cameFromRoute) return alert("Select a bus from route.");
    distanceActive = !distanceActive;
    if (distanceActive) {
      followUser = true;
      showDistanceRoute();
      fitUserAndBus();
    } else {
      hideDistanceRoute();
    }
  });

  const demoButton = L.control({ position: "bottomright" });
  demoButton.onAdd = () => {
    const div = L.DomUtil.create("div", "leaflet-bar leaflet-control leaflet-control-custom");
    div.style.border = "none";
    div.style.background = "transparent";
    div.style.overflow = "visible";
    L.DomEvent.disableClickPropagation(div);

    const button = L.DomUtil.create("button", "", div);
    button.id = "demoPlayBtn";
    button.style.cssText = `width:2.4rem;height:2.4rem;padding:0;border:none;background: #B29CEE;cursor:pointer;
                            box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;`;
    demoButtonIcon = L.DomUtil.create("i", "fas fa-play", button);

    L.DomEvent.on(button, 'click', (e) => {
      L.DomEvent.stopPropagation(e);

      // When user directly goes to track without selecting from route
      if (!cameFromRoute) {
        return alert("Select a bus from route.");
      }

      const selectedBusId = getSelectedBusId();
      if (!selectedBusId) {
        return alert("Select a bus from route.");
      }

      const targetMarker = getTargetBusMarker();
      if (!targetMarker) {
        return alert("Select a bus from route.");
      }

      if (!userMarker) {
        return alert("User location not available!");
      }

      if (demoRunning) {
        demoBus.stop(true);
        return;
      }

      // 1. Path must be made and shown on map
      distanceActive = true;
      showDistanceRoute();

      // 2. Start demo simulation along the road path
      startDemoAlongRoad(selectedBusId, targetMarker);
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
        if (followUser && !initViewDone) {
          if (cameFromRoute && getSelectedBusId()) maybeAutoFitToSelected(getSelectedBusId());
          else maybeAutoFitToAll();
        }
      },
      err => {
        console.warn("Geolocation warning:", err.message);
        // Fallback marker so user location is available
        if (!userMarker) {
          updateUserMarker(DEFAULT_CENTER[0], DEFAULT_CENTER[1]);
          if (followUser && !initViewDone) {
            if (cameFromRoute && getSelectedBusId()) maybeAutoFitToSelected(getSelectedBusId());
            else maybeAutoFitToAll();
          }
        }
      },
      { enableHighAccuracy: true, maximumAge: 2000, timeout: 5000 }
    );
  } else {
    updateUserMarker(DEFAULT_CENTER[0], DEFAULT_CENTER[1]);
    if (followUser && !initViewDone) {
      if (cameFromRoute && getSelectedBusId()) maybeAutoFitToSelected(getSelectedBusId());
      else maybeAutoFitToAll();
    }
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

        if (cameFromRoute && selectedBusId) {
          const found = busData.find(b => {
            const idA = b.id !== undefined ? String(b.id) : null;
            const idB = b.l_id !== undefined ? String(b.l_id) : null;
            return idA === selectedBusId || idB === selectedBusId;
          });

          if (found) {
            const idStr = String(found.id ?? found.l_id ?? selectedBusId);
            const lat = parseFloat(found.latitude);
            const lng = parseFloat(found.longitude);

            if (demoRunning) {
              // Active simulation: do not overwrite bus marker position during animation
            } else {
              Object.keys(busMarkers).forEach(id => { if (id !== idStr) removeBusMarker(id); });
              createOrUpdateBusMarker(idStr, lat, lng, found);
              maybeAutoFitToSelected(idStr);
            }
          } else {
            if (!demoRunning) removeAllBusMarkers();
          }
        } else {
          // Direct navigation to Track: show all buses
          const seen = new Set();
          busData.forEach(bus => {
            const idStr = String(bus.id ?? bus.l_id ?? '');
            if (!idStr) return;
            const lat = parseFloat(bus.latitude);
            const lng = parseFloat(bus.longitude);
            if (!isValidLatLng(lat, lng)) return;
            seen.add(idStr);
            createOrUpdateBusMarker(idStr, lat, lng, bus);
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

(() => {
  // --- CONFIG ---
  const MIN_SEND_INTERVAL_MS = 3000;  // Throttle: don't send faster than 3s
  const HEARTBEAT_INTERVAL_MS = 12000; // Heartbeat: send at least every 12s even if stationary
  const DEFAULT_CENTER = [27.7172, 85.3240]; // Kathmandu, Nepal
  const DEFAULT_ZOOM = 14;

  // --- Parse URL for center/zoom if passed ---
  const urlParams = new URLSearchParams(window.location.search);
  let initialCenter = DEFAULT_CENTER.slice();
  let initialZoom = DEFAULT_ZOOM;

  const centerParam = urlParams.get('center');
  const zoomParam = parseInt(urlParams.get('zoom'), 10);
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
  let busMarker = null;
  let followBus = true;
  let lastSentTime = 0;
  let lastSentLat = null;
  let lastSentLng = null;
  let isSending = false;

  // --- Status banner indicator ---
  const statusControl = L.control({ position: "topright" });
  let statusDiv = null;
  statusControl.onAdd = () => {
    statusDiv = L.DomUtil.create("div", "driver-status-badge");
    statusDiv.style.cssText = `background: rgba(0,0,0,0.75); color: #fff; padding: 6px 12px; border-radius: 20px;
                              font-size: 12px; font-family: sans-serif; display: flex; align-items: center; gap: 6px;
                              box-shadow: 0 2px 6px rgba(0,0,0,0.3); margin-top: 10px; margin-right: 10px;`;
    statusDiv.innerHTML = `<span style="width:8px;height:8px;background:#ffc107;border-radius:50%;display:inline-block;"></span> Initializing GPS...`;
    return statusDiv;
  };
  statusControl.addTo(map);

  const updateStatus = (text, color = '#28a745') => {
    if (statusDiv) {
      statusDiv.innerHTML = `<span style="width:8px;height:8px;background:${color};border-radius:50%;display:inline-block;"></span> ${text}`;
    }
  };

  // --- Smooth marker animation ---
  const animateBusMarkerTo = (targetLat, targetLng, duration = 600) => {
    if (!busMarker) {
      busMarker = L.marker([targetLat, targetLng], {
        icon: L.divIcon({
          html: '<i class="fas fa-bus" style="font-size:25px;color:#5A00A3"></i>',
          iconSize: [25, 25],
          iconAnchor: [12, 12],
          className: ''
        })
      }).addTo(map).bindPopup("<b>Your Bus (Broadcasting)</b>");
      if (followBus) map.setView([targetLat, targetLng], 15);
      return;
    }

    const startLatLng = busMarker.getLatLng();
    if (Math.abs(startLatLng.lat - targetLat) < 0.000001 && Math.abs(startLatLng.lng - targetLng) < 0.000001) {
      return;
    }
    const startTime = performance.now();

    const step = (now) => {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const ease = 1 - Math.pow(1 - progress, 2);

      const curLat = startLatLng.lat + (targetLat - startLatLng.lat) * ease;
      const curLng = startLatLng.lng + (targetLng - startLatLng.lng) * ease;
      busMarker.setLatLng([curLat, curLng]);

      if (progress < 1) {
        requestAnimationFrame(step);
      }
    };
    requestAnimationFrame(step);

    if (followBus) {
      map.panTo([targetLat, targetLng], { animate: true, duration: 0.5 });
    }
  };

  // --- Throttled location transmitter ---
  const sendLocationThrottled = (lat, lng) => {
    const now = Date.now();
    const timeSinceLast = now - lastSentTime;

    // Check distance moved roughly in meters
    let distanceMoved = 0;
    if (lastSentLat !== null && lastSentLng !== null) {
      const dLat = (lat - lastSentLat) * 111320;
      const dLng = (lng - lastSentLng) * 111320 * Math.cos(lat * Math.PI / 180);
      distanceMoved = Math.sqrt(dLat * dLat + dLng * dLng);
    }

    // Skip if sent too recently unless significant movement (> 3m) or heartbeat interval exceeded
    if (timeSinceLast < MIN_SEND_INTERVAL_MS && distanceMoved < 3) {
      return;
    }
    if (isSending) return;

    isSending = true;
    $.ajax({
      url: 'trackP.php',
      type: 'POST',
      dataType: 'json',
      data: { latitude: lat, longitude: lng },
      success: res => {
        lastSentTime = Date.now();
        lastSentLat = lat;
        lastSentLng = lng;
        if (res && res.status === "success") {
          updateStatus("Live Broadcasting", "#28a745");
        } else {
          updateStatus(res?.message || "Location warning", "#ffc107");
        }
      },
      error: (xhr, status, err) => {
        console.error("Error broadcasting location:", err);
        updateStatus("Connection issue", "#dc3545");
      },
      complete: () => {
        isSending = false;
      }
    });
  };

  // --- Disable follow mode on map interaction ---
  map.on('dragstart', () => followBus = false);
  map.on('zoomstart', () => followBus = false);

  // Recenter button
  const recenterControl = L.control({ position: "bottomright" });
  recenterControl.onAdd = () => {
    const div = L.DomUtil.create("div", "leaflet-bar leaflet-control");
    const button = L.DomUtil.create("button", "", div);
    button.title = "Re-center on bus";
    button.style.cssText = `width:2.6rem;height:2.6rem;background:#7A55D4;color:#fff;border:none;border-radius:50%;cursor:pointer;
                            box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;`;
    button.innerHTML = `<i class="fas fa-crosshairs" style="font-size:1.1rem;"></i>`;
    L.DomEvent.on(button, 'click', (e) => {
      L.DomEvent.stopPropagation(e);
      followBus = true;
      if (busMarker) {
        map.flyTo(busMarker.getLatLng(), 16);
      }
    });
    return div;
  };
  recenterControl.addTo(map);

  // --- Real-time GPS Watching ---
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
      pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        animateBusMarkerTo(lat, lng);
        sendLocationThrottled(lat, lng);
      },
      err => {
        console.warn("Driver GPS warning:", err.message);
        updateStatus("GPS Signal Lost", "#dc3545");
      },
      { enableHighAccuracy: true, maximumAge: 1000, timeout: 8000 }
    );
  } else {
    alert("Geolocation is not supported by your browser. Live tracking cannot operate.");
    updateStatus("No GPS Support", "#dc3545");
  }
})();

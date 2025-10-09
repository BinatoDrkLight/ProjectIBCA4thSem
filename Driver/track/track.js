(() => {
  // --- CONFIG ---
  const POLL_INTERVAL_MS = 5000;
  const DEFAULT_CENTER = [51.505, -0.09];
  const DEFAULT_ZOOM = 14;

  // --- Detect if came from routeH.php ---
  const cameFromRoute = (document.referrer || '').includes('routeH.php');
  if (cameFromRoute) return;

  // --- Parse URL for center/zoom ---
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
  let busMarker = null;
  let followBus = true;

  // --- Helper functions ---
  const round = n => Math.round(n * 1e6) / 1e6;

  const updateBusMarker = (lat, lng) => {
    if (isNaN(lat) || isNaN(lng)) return;
    if (busMarker) {
      busMarker.setLatLng([lat, lng]);
    } else {
      busMarker = L.marker([lat, lng], {
        icon: L.divIcon({
          html: '<i class="fas fa-bus" style="font-size:25px;color: #5A00A3"></i>',
          iconSize: [25, 25],
          iconAnchor: [12, 12],
          className: ''
        })
      }).addTo(map);
    }
    if (followBus) map.setView([lat, lng]);
  };

  const sendLocation = (lat, lng) => {
    $.ajax({
      url: 'trackP.php',
      type: 'POST',
      data: { latitude: lat, longitude: lng },
      success: res => {
        if (res?.status === "success") {
          // console.log("Location successfully saved");
        } else {
          console.warn("Location update:", res?.message || "unknown response");
        }
      },
      error: (xhr, status, err) => {
        console.error("Error sending location:", err);
      }
    });
  };

  // --- Update URL on move ---
  map.on('moveend', () => {
    const center = map.getCenter();
    const zoom = map.getZoom();
    const newUrl = `${window.location.protocol}//${window.location.host}${window.location.pathname}?center=${round(center.lat)},${round(center.lng)}&zoom=${zoom}`;
    window.history.replaceState({}, '', newUrl);
  });

  // --- Disable follow mode when user interacts ---
  map.on('dragstart', () => followBus = false);
  map.on('zoomstart', () => followBus = false);

  // --- Real-time tracking ---
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
      pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        updateBusMarker(lat, lng);
        sendLocation(lat, lng);
      },
      err => console.warn("Geolocation error:", err),
      { enableHighAccuracy: true, maximumAge: 1000, timeout: 5000 }
    );
  } else {
    alert("Geolocation is not supported by your browser.");
  }

  // --- Manual periodic fallback update ---
  const updateOnce = () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        updateBusMarker(lat, lng);
        sendLocation(lat, lng);
      });
    }
  };

  setInterval(updateOnce, POLL_INTERVAL_MS);
})();

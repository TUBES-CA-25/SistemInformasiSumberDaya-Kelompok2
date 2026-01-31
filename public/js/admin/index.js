document.addEventListener("DOMContentLoaded", function () {
  fetchStats();
});

function fetchStats() {
  fetch(API_URL + "/dashboard/stats")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const stats = data.data;
        document.getElementById("count-asisten").textContent = stats.asisten;
        document.getElementById("count-lab").textContent = stats.laboratorium;
        document.getElementById("count-alumni").textContent = stats.alumni;
        document.getElementById("count-mk").textContent = stats.matakuliah;
      }
    })
    .catch((error) => console.error("Error fetching stats:", error));
}

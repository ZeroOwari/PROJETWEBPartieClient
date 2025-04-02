document.addEventListener("DOMContentLoaded", function () {
    let dataSets = {
        all: [60, 40],
        btp: [10, 10],
        informatique: [70, 30],
        generaliste: [55, 45]
    };

    let currentFilter = "all";

    let ctx = document.getElementById('stageChart').getContext('2d');

    let stageChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Stage obtenu', 'Stage non obtenu'],
            datasets: [{
                data: dataSets[currentFilter],
                backgroundColor: ['#7B8EEE', '#5A1763']
            }]
        }
    });

    document.getElementById("filterBtn").addEventListener("click", function () {
        let filterContainer = document.getElementById("filterContainer");
        filterContainer.style.display = (filterContainer.style.display === "block") ? "none" : "block";
    });

    document.querySelectorAll(".filter").forEach(button => {
        button.addEventListener("click", function () {
            let filter = this.getAttribute("data-filter");
            stageChart.data.datasets[0].data = dataSets[filter];
            stageChart.update();
        });
    });
});


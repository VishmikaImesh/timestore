window.addEventListener("load", loadRevenue);

document.getElementById("revenuePeriod").addEventListener("change", loadRevenue);

let revenueChart = null;

function loadRevenue() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenuePeriod = document.getElementById("revenuePeriod");

    var form = new FormData();
    form.append('revenuePeriod', revenuePeriod.value)

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            if (revenueChart) {
                revenueChart.destroy();
            }
            alert(request.responseText);

            var jsonObject = JSON.parse(request.responseText)

            // Create Red Gradient to match your theme
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(220, 53, 69, 0.2)'); // Bootstrap Danger Color (Low Opacity)
            gradient.addColorStop(1, 'rgba(220, 53, 69, 0)'); // Fade out

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jsonObject.dates,
                    datasets: [{
                        label: 'Revenue (Rs)',
                        data: jsonObject.revenues,
                        borderColor: '#dc3545', // Your Red Accent
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.3, // Slightly less curve for a cleaner look
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#dc3545',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    animation: {
                        duration: 2000, // Animation duration in milliseconds (2 seconds)
                        easing: 'easeInOutQuart', // Smooth starting and stopping
                        loop: false, // Don't loop the animation

                        // Specific animation for the Y-axis (growing up)
                        y: {
                            from: 0 // Start from y=0
                        }
                    },
                    // -----------------------------------

                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#000',
                            titleFont: { size: 13 },
                            bodyFont: { size: 14, weight: 'bold' },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5] },
                            ticks: {
                                color: '#6c757d',
                                font: { weight: 'bold' },
                                callback: function (value) {
                                    return 'Rs ' + value / 1000 + 'k';
                                }
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#6c757d',
                                font: { weight: 'bold' }
                            }
                        }
                    }
                }
            });
        }
    }

    request.open("POST", "/api/admin/revenueData", true);
    request.send(form)

}
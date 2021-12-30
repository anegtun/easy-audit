$(document).ready(function() {

    $('.history-container').each(function(i, container) {
        const templateId = $(container).attr('data-template-id');
        const labels = [];
        const data = [];
        $(container).find('.audit-history-total').each(function(j, item) {
            labels.push($(item).attr('data-audit-date'));
            data.push($(item).text());
        });
        
        new Chart(document.getElementById("audit-history-chart-"+templateId).getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    backgroundColor: "#1d71b8",
                    data: data,
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                },
            }
        });
    });


});
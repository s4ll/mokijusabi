@props(['data' => []])

<div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
    <div class="flex justify-between items-start w-full">
        <div class="flex-col items-center">
            <div class="flex items-center mb-1">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white me-1">Pie Chart</h5>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="py-6" id="pie-chart"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const getChartOptions = () => {
            return {
                series: {!! json_encode(collect($data)->pluck('value')) !!},
                labels: {!! json_encode(collect($data)->pluck('label')) !!},
                colors: ["#1C64F2", "#16BDCA", "#9061F9", "#F59E0B", "#10B981", "#EF4444", "#EAB308", "#8B5CF6"],
                chart: {
                    height: 420,
                    width: "100%",
                    type: "pie",
                },
                stroke: {
                    colors: ["white"],
                },
                plotOptions: {
                    pie: {
                        size: "100%",
                        dataLabels: {
                            offset: -25
                        }
                    },
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontFamily: "Inter, sans-serif",
                        fontSize: "14px"
                    },
                },
                legend: {
                    position: "bottom",
                    fontFamily: "Inter, sans-serif",
                    labels: {
                        colors: '#fff'
                    }
                },
            }
        }

        if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
            chart.render();
        }
    });
</script>

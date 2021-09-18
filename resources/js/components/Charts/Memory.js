import React from "react";
import { defaults, Line } from "react-chartjs-2";

defaults.animation = false;

const Memory = (props) => {
    const state = {
        labels: Array(25).fill(""),
        datasets: [
            {
                label: `Memory Usage (MB)`,
                fill: true,
                data: props.data,
            },
        ],
    };
    return (
        <Line
            data={state}
            options={{
                legend: {
                    display: false,
                },
                tooltips: {
                    enabled: false,
                },
                animation: {
                    duration: 0,
                },
                elements: {
                    point: {
                        radius: 0,
                    },
                    line: {
                        tension: 0.3,
                        backgroundColor: "#FFC107",
                        borderColor: "#FFC107",
                    },
                },
                scales: {
                    xAxes: [
                        {
                            ticks: {
                                display: false,
                            },
                            gridLines: {
                                display: false,
                            },
                        },
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                drawTicks: false,
                                color: "rgba(229, 232, 235, 0.15)",
                                zeroLineColor: "rgba(15, 178, 184, 0.45)",
                                zeroLineWidth: 3,
                            },
                            ticks: {
                                min: 0,
                                stepSize: 50,
                            },
                        },
                    ],
                },
            }}
        ></Line>
    );
};

export default Memory;

import React from "react";
import { defaults, Line } from "react-chartjs-2";

defaults.animation = false;

const CPU = (props) => {
    const state = {
        labels: Array(25).fill(""),
        datasets: [
            {
                label: `CPU Usage (%)`,
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
                        backgroundColor: "#2aebe1",
                        borderColor: "#2aebe1",
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
                                stepSize: 1,
                            },
                        },
                    ],
                },
            }}
        ></Line>
    );
};

export default CPU;

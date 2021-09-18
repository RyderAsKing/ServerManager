import React from "react";
import { Bar } from "react-chartjs-2";

const Network = (props) => {
    const state = {
        labels: Array(5).fill(""),
        datasets: [
            {
                cubicInterpolationMode: "monotone",
                label: `Transmited (MB)`,
                fill: false,
                lineTension: 0.4,
                backgroundColor: "#FFF",
                borderColor: "#FFF",
                borderCapStyle: "butt",
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: "miter",
                pointBorderColor: "#FFF",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#FFF",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: props.data.tx,
                spanGaps: false,
            },
            {
                cubicInterpolationMode: "monotone",
                label: `Received (MB)`,
                fill: false,
                lineTension: 0.4,
                backgroundColor: "#42fca5",
                borderColor: "#42fca5",
                borderCapStyle: "butt",
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: "miter",
                pointBorderColor: "#42fca5",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#42fca5",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: props.data.rx,
                spanGaps: false,
            },
        ],
    };
    return (
        <Bar
            data={state}
            options={{
                responsive: true,
                title: {
                    display: true,
                    text: "Network",
                    fontSize: 15,
                },
                legend: {
                    display: true,
                    position: "top",
                },
            }}
        ></Bar>
    );
};

export default Network;

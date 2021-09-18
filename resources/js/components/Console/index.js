import React from "react";
import { Terminal } from "xterm";
import { FitAddon } from "xterm-addon-fit";

let term;
let lastData = "";
const fitAddon = new FitAddon();

export default class Console extends React.Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const TERMINAL_PRELUDE = "\u001b[1m\u001b[33mcontainer~ \u001b[0m";

        // Styling
        const theme = {
            background: "#131A20",
            cursor: "transparent",
            black: "#131A20",
            red: "#E54B4B",
            green: "#9ECE58",
            yellow: "#FAED70",
            blue: "#396FE2",
            magenta: "#BB80B3",
            cyan: "#2DDAFD",
            white: "#d0d0d0",
            brightBlack: "rgba(255, 255, 255, 0.2)",
            brightRed: "#FF5370",
            brightGreen: "#C3E88D",
            brightYellow: "#FFCB6B",
            brightBlue: "#82AAFF",
            brightMagenta: "#C792EA",
            brightCyan: "#89DDFF",
            brightWhite: "#ffffff",
            selection: "#FAF089",
        };

        term = new Terminal({
            disableStdin: true,
            cursorStyle: "underline",
            allowTransparency: true,
            fontSize: 12,
            fontFamily: "Menlo, Monaco, Consolas, monospace",
            rows: 30,
            theme: theme,
        });

        // Load Fit Addon
        term.loadAddon(fitAddon);

        // Open the terminal in #terminal-container
        term.open(document.getElementById("console"));

        //Write text inside the terminal
        // term.write(TERMINAL_PRELUDE);

        // Make the terminal's size and geometry fit the size of #terminal-container
        fitAddon.fit();

        // term.onKey((key) => {
        //     const char = key.domEvent.key;
        //     if (char === "Enter") {
        //         this.prompt();
        //     } else if (char === "Backspace") {
        //         term.write("\b \b");
        //     } else {
        //         term.write(char);
        //     }
        // });
    }

    logEnter = (log) => {
        if (log === undefined) return;
        term.write("\r\n" + log);
        lastData = log;
    };
    render() {
        if (term != undefined) {
            if (this.props.data[0] != lastData) {
                this.logEnter(this.props.data[0]);
            }
        }

        return (
            <>
                <div style={this.props.style}>
                    <div
                        id="console"
                        style={{
                            height: this.props.height,
                            width: this.props.width,
                        }}
                    ></div>
                    <div id="terminal_input" className="form-group no-margin">
                        <div className="input-group">
                            <div className="input-group-addon terminal_input--prompt">
                                container:~/$
                            </div>
                            <input
                                type="text"
                                className="terminal_input--input text-white"
                                style={{ marginLeft: "5px", width: "80%" }}
                                onChange={this.props.inputEvent}
                                onKeyDown={this.props.keyEvent}
                                value={this.props.inputValue}
                            />
                        </div>
                    </div>
                </div>
            </>
        );
    }
}

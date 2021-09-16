import React from "react";

const Footer = () => {
    return (
        <>
            <footer
                className="fixed-bottom page-footer font-small blue"
                style={{ marginTop: "5px" }}
            >
                <div className="footer-copyright text-center py-3">
                    © 2021 Copyright{" "}
                    <a href="https://github.com/RyderAsKing/ServerManager">
                        Server Manager
                    </a>
                </div>
            </footer>
        </>
    );
};

export default Footer;

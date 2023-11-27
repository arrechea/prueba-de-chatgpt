import ReactDOM from "react-dom";
import React, { Component } from "react";
import PanZoom from "react-easy-panzoom";

export default class MapZoom extends React.Component {

    /**
     *
     * @returns {XML}
     */
    render() {

        return (
            <div className="MapMeetingZoom">
                <PanZoom style={{ height: 400, border: "solid 2px red" }}>
                    <div
                    style={{
                        backgroundColor: "grey",
                        padding: 20,
                        color: "white",
                        width: 200,
                        borderRadius: 6
                    }}
                    >
                    This div can be dragged
                    </div>
                </PanZoom>

            </div>
        )
    }

}
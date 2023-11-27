import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";
import GridButtons from "./GridButtons";
import CellGrid from "./CellGrid";
export default class GridMapGenerator extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            map: StoreMapGenerator.get('map'),
            room_image: StoreMapGenerator.get('room_image')
        };
        StoreMapGenerator.addListener(this.ListenerStore.bind(this))
    }

    ListenerStore() {
        this.setState({
            map: StoreMapGenerator.get('map'),
            room_image: StoreMapGenerator.get('room_image')
        });
    }

    /**
     *
     * @returns {*}
     */
    getCurrentMap() {
        return this.state.map;
    }

    render() {
        let component = this;
        let map = component.getCurrentMap();
        let cellNumber = 1;
        let roomImage = component.state.room_image;

        return (
            <div className="GridMapGenerator">
                <div className="GridMapGenerator--canvas" style={{
                    backgroundImage: `url(${roomImage})`
                }}>
                    {map.map((row, rowIndex) => {
                        //This a row
                        let cells = null;
                        if (row.length) {
                            cells = row.map((cell, cellIndex) => {
                                let cellCurrentNumber = cell && cell.type === 'public' ? cellNumber : null;
                                // if (cellCurrentNumber !== null) {
                                //     cellNumber++;
                                // }
                                return (
                                    <CellGrid
                                        rowIndex={rowIndex}
                                        cellIndex={cellIndex}
                                        // cellNumber={cell.object.position_text}
                                        cellNumber={cellNumber}
                                        // todo checar el nombre de la posicion
                                        position={cell}
                                        key={`GridMapGenerator--cell--${rowIndex}--${cellIndex}`}
                                    />
                                )
                            })
                        }
                        return (
                            <div
                                className="CellRow"
                                data-row={rowIndex}
                                key={`GridMapGenerator--row--${rowIndex}`}
                            >
                                {cells}
                            </div>
                        );
                    })}
                </div>
                <div className="GridMapGenerator--buttonsTop">
                    <GridButtons
                        triggerPlus={StoreMapGenerator.addTopRow}
                        triggerMinus={StoreMapGenerator.removeTopRow}
                    />
                </div>
                <div className="GridMapGenerator--buttonsRight">
                    <GridButtons
                        triggerPlus={StoreMapGenerator.addColumnRight}
                        triggerMinus={StoreMapGenerator.removeColumnRight}
                    />
                </div>
                <div className="GridMapGenerator--buttonsBottom">
                    <GridButtons
                        triggerPlus={StoreMapGenerator.addBottomRow}
                        triggerMinus={StoreMapGenerator.removeBottomRow}
                    />
                </div>
                <div className="GridMapGenerator--buttonsLeft">
                    <GridButtons
                        triggerPlus={StoreMapGenerator.addColumnLeft}
                        triggerMinus={StoreMapGenerator.removeColumnLeft}
                    />
                </div>
            </div>
        )
    }
}

import React from 'react'
import PositionGridMapSelector from "./PositionGridMapSelector";

export default class CellGridMapSelector extends React.Component {
    static get defaultProps() {
        return {
            rowIndex: null,
            cellIndex: null,
            cellNumber: null,
            cell: null,
        }
    }

    render() {
        let {rowIndex, cellIndex, cellNumber, cell} = this.props;

        return (
            <div
                className="CellGrid"
                data-row={rowIndex}
                data-cell={cellIndex}
            >
                {cell ?
                    <PositionGridMapSelector cellNumber={cellNumber} cell={cell} rowIndex={rowIndex} cellIndex={cellIndex}/> : null}
            </div>
        )
    }
}

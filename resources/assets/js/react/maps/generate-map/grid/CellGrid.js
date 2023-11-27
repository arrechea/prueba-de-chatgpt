import React from 'react'
import PositionGrid from "./PositionGrid";
import StoreMapGenerator from "../StoreMapGenerator";
import PositionTooltipGrid from "./PositionTooltipGrid";
import TextEdit from "./TextEdit";

export default class CellGrid extends React.Component {
    static get defaultProps() {
        return {
            rowIndex: null,
            cellIndex: null,
            cellNumber: null,
            position: null,
        }
    }

    constructor() {
        super();
        this.state = {
            showEdit: false,
        }
    }

    editText(e) {
        let currentShow = this.state.showEdit;

        if (currentShow) {
            this.setState({
                showEdit: false,
            })
        } else {
            this.setState({
                showEdit: true,
            })
        }
    }

    /**
     * Add position to cell only if this position is empty
     */
    addPositionToCell() {
        let {rowIndex, cellIndex, position} = this.props;
        if (!position) {
            let newPosition = StoreMapGenerator.get('position_selected');
            StoreMapGenerator.setPositionToCell(newPosition, rowIndex, cellIndex);
        }
    }

    render() {
        let {rowIndex, cellIndex, cellNumber, position} = this.props;
        return (
            <div
                className="CellGrid"
                data-row={rowIndex}
                data-cell={cellIndex}
                onClick={this.addPositionToCell.bind(this)}
            >
                {position ? <PositionGrid position={position} rowIndex={rowIndex} cellIndex={cellIndex}
                                          showEdit={this.state.showEdit} editText={this.editText.bind(this)}/> : null}
                {position ? <PositionTooltipGrid position={position} cellNumber={cellNumber}/> : null}
                {position ?
                    <TextEdit position={position} cellIndex={cellIndex} rowIndex={rowIndex} cellNumber={cellNumber}
                              showEdit={this.state.showEdit}
                              editText={this.editText.bind(this)}/> : null}
            </div>
        )
    }
}

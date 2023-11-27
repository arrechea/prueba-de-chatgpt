import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";

export default class TextEdit extends React.Component {
    constructor(props) {
        super();
        this.saveText = this.saveText.bind(this);
    }

    saveText(e, text) {
        let {rowIndex, cellIndex} = this.props;
        let pos = Object.assign({}, this.props.position);
        pos.text = text;
        StoreMapGenerator.setPositionToCell(pos, rowIndex, cellIndex);
    }

    render() {
        let component = this;
        let {rowIndex, cellIndex, showEdit, editText, position, cellNumber} = this.props;

        return (
            <div className={`CellGrid--position--text ${cellIndex === 0 ? 'first-cell' : ''}`} hidden={!showEdit}>
                <input className={'CellGrid--position--text--input'}
                       defaultValue={position.text ? position.text : cellNumber}
                       id={`CellGrid--${rowIndex}--${cellIndex}--text`}/>
                <a className={'CellGrid--position--text--button'} onClick={function (e) {
                    let text = $(`#CellGrid--${rowIndex}--${cellIndex}--text`).val();
                    component.saveText(e, text);
                    editText(e);
                }}><i
                    className={'material-icons'}>save</i></a>
            </div>
        )
    }
}

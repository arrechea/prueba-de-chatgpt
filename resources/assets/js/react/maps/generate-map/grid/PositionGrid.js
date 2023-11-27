import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";

export default class PositionGrid extends React.Component {
    static get defaultProps() {
        return {
            position: null,
            rowIndex: null,
            cellIndex: null,
        }
    }

    /**
     * Helper to calculate dimentions
     * @param dimention
     * @returns {string}
     */
    calculateRealDimention(dimention) {
        let response = 100;

        if (!isNaN(dimention)) {
            //is number
            response = response * dimention;
        }

        return `${response}%`
    }

    /**
     * Remove position
     */
    removePosition() {
        let {rowIndex, cellIndex} = this.props;
        StoreMapGenerator.setPositionToCell(null, rowIndex, cellIndex);
    }

    render() {
        let component = this;
        let {position} = component.props;
        let {image, width, height} = position;
        let lang = StoreMapGenerator.get('lang');

        return (
            <div className="CellGrid--position" style={{
                backgroundImage: `url(${image})`,
                width: component.calculateRealDimention(width),
                height: component.calculateRealDimention(height)
            }}>
                <div className="CellGrid--position--remove" onClick={component.removePosition.bind(this)}>
                    <i className="material-icons">clear</i>
                </div>
                {position.type === 'public' ?
                    <div className="CellGrid--position--edit" onClick={component.props.editText}>
                        <i className="material-icons">{component.props.showEdit ? 'clear' : 'edit'}</i>
                    </div> : null}
            </div>
        )
    }
}

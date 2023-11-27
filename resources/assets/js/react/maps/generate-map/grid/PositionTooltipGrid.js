import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";

export default class PositionTooltipGrid extends React.Component {
    static get defaultProps() {
        return {
            position: null,
            cellNumber: null,
        }
    }

    render() {
        let component = this;
        let {position, cellNumber} = component.props;
        let {name, image, image_selected, image_disabled, type, text} = position;
        let lang = StoreMapGenerator.get('lang');

        return (
            <div className="CellGrid--tooltip">
                <div className="CellGrid--name">
                    {
                        cellNumber !== null ?
                            (
                                <span className="CellGrid--number">#{text ? text : cellNumber}</span>
                            )
                            : null
                    }
                    {name}
                    <span className="CellGrid--type">
                        ({lang[type]})
                    </span>
                </div>
                <div className="CellGrid--tooltip--images">
                    <div className="CellGrid--tooltip--images--image">
                        <img src={image} alt={lang['image']}/>
                        {lang['position.image']}
                    </div>
                    <div className="CellGrid--tooltip--images--image">
                        <img src={image_selected} alt={lang['image_selected']}/>
                        {lang['position.image_selected']}
                    </div>
                    <div className="CellGrid--tooltip--images--image">
                        <img src={image_disabled} alt={lang['image_disabled']}/>
                        {lang['position.image_disabled']}
                    </div>
                </div>
            </div>
        )
    }
}

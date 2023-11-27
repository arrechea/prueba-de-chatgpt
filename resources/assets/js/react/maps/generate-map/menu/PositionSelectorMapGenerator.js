import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";
import Slider from "react-slick";
import PositionSliderOptions from './PositionSliderOptions'
import PositionTooltipGrid from "../grid/PositionTooltipGrid";


export default class PositionSelectorMapGenerator extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            positionSelected: StoreMapGenerator.get('position_selected'),
        };
        StoreMapGenerator.addSegmentedListener(['position_selected'], this.ListenerPositionSelected.bind(this))
    }

    ListenerPositionSelected() {
        this.setState({
            positionSelected: StoreMapGenerator.get('position_selected'),
        })
    }

    getPositions() {
        return StoreMapGenerator.get('location_positions');
    }

    selectPosition(position) {
        StoreMapGenerator.set('position_selected', position);
    }

    positionIsSelected(position) {
        let positionSelected = this.state.positionSelected;

        return !!positionSelected && positionSelected.id === position.id;
    }

    render() {
        let component = this;
        let positions = component.getPositions();
        let search = StoreMapGenerator.get('search');
        if (!positions.length) {
            return null;
        }

        return (
            <div className="PositionSelectorMapGenerator">
                <Slider {...PositionSliderOptions} id="PositionSelectorMapGenerator">
                    {positions.map((position) => {
                        let extraClass = component.positionIsSelected(position) ? 'PositionSelectorMapGenerator--position__selected' : '';
                        if (
                            search !== ''
                            &&
                            search !== null
                            &&
                            position.name.indexOf(search) !== -1
                        ) {
                            return null;
                        }
                        return (
                            <span className={`PositionSelectorMapGenerator--position ${extraClass}`}
                                  key={`PositionSelectorMapGenerator--position--${position.id}`}
                                  onClick={component.selectPosition.bind(component, position)}>
                                <span className="PositionSelectorMapGenerator--position--image"
                                      style={{backgroundImage: `url(${position.image})`}}/>
                                {position ? <PositionTooltipGrid position={position}/> : null}
                            </span>
                        )
                    })}
                </Slider>
            </div>
        )
    }
}

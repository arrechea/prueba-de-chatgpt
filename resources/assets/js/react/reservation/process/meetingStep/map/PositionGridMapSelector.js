import React from 'react'
import StoreReservation from "../../StoreReservation";
import StoreMapSelector from "./StoreMapSelector";

export default class PositionGridMapSelector extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            mouseOver: false
        }
    }

    static get defaultProps() {
        return {
            cell: null,
            rowIndex: null,
            cellIndex: null,
            cellNumber: null,
        }
    }

    getRowIndex() {
        return this.props.rowIndex;
    }

    getCellIndex() {
        return this.props.cellIndex;
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
     *
     */
    getCell() {
        return this.props.cell;
    }

    /**
     *
     */
    getCellNumber() {
        return this.props.cellNumber;
    }

    getCoach() {
        let meeting = StoreReservation.get('meeting');
        return !!meeting ? meeting.staff : null;
    }

    getCellName() {
        let position = this.getObject();
        let coach = this.getCoach();
        if (this.isCoach() && coach) {
            return coach.name;
        } else {
            if (position.position_text == null) {
                return position.position_number;
            } else {
                return position.position_text;
            }
        }
    }


    /**
     *
     */
    getStatus() {
        let cell = this.getCell();
        return cell.status;
    }

    getObject() {
        let cell = this.getCell();
        return cell.object;
    }

    /**
     *
     */
    getPosition() {
        let object = this.getObject();

        return object.positions;
    }

    isMouseOver() {
        return this.state.mouseOver === true;
    }

    isPublic() {
        let position = this.getPosition();
        return position.type === 'public'
    }

    isPrivate() {
        let position = this.getPosition();
        return position.type === 'private'
    }

    isCoach() {
        let position = this.getPosition();
        return position.type === 'coach'
    }

    getPositionType() {
        let position = this.getPosition();
        return position.type;
    }

    /**
     *
     * @returns {string}
     */
    getCurrentImageToDisplay() {
        let position = this.getPosition();
        let status = this.getStatus();
        let {image, image_disabled, image_selected} = position;
        let response = '';
        let mouseOver = this.isMouseOver();
        let coach = this.getCoach();

        if (this.isCoach() && coach) {
            response = coach.picture_web;
        } else {
            switch (status) {
                case 'selected':
                    response = image_selected;
                    break;
                case 'disabled':
                    response = image_disabled;
                    break;
                case 'enabled':
                    response = mouseOver && this.isPublic() ? image_selected : image;
                    break;
                default:
                    response = image;
                    break;
            }
        }

        return response;
    }

    /**
     *
     * @returns {boolean}
     */
    isClickable() {
        return (
            this.isPublic()
            &&
            (
                this.isSelected() || this.isEnabled()
            )
        );
    }

    isSelected() {
        let status = this.getStatus();
        return status === 'selected';
    }

    isEnabled() {
        let status = this.getStatus();
        return status === 'enabled';
    }

    isDisabled() {
        let status = this.getStatus();
        return status === 'disabled';
    }

    setMouseOver(isOver) {
        this.setState({
            mouseOver: isOver
        })
    }

    clickInPosition() {
        if (this.isClickable()) {
            let row = this.getRowIndex();
            let cell = this.getCellIndex();
            StoreMapSelector.clickInPosition(row, cell);
        }
    }

    displayCellText() {
        let cellNumber = this.getCellNumber();
        let cell_text = this.getCellName();

        if (this.isCoach()) {
            return (
                <div className="CellGrid--position--coachName">
                    <span>{cell_text}</span>
                </div>
            )
        } else {
            return cellNumber ? (
                <div className="CellGrid--position--cellNumber">
                    <span>{cell_text}</span>
                </div>
            ) : null;
        }

    }

    render() {
        let component = this;
        let position = this.getPosition();
        let {width, height} = position;
        let currentImage = this.getCurrentImageToDisplay();
        let positionType = component.getPositionType();
        let status = this.getStatus();

        let lang = StoreReservation.get('lang');
        let classClickable = this.isClickable() ? 'CellGrid--position__clickable' : '';

        return (
            <div
                className={`CellGrid--position ${classClickable}`}
                data-positiontype={positionType}
                data-status={status}
                style={{
                    backgroundImage: `url(${currentImage})`,
                    width: component.calculateRealDimention(width),
                    height: component.calculateRealDimention(height)
                }}
                onMouseLeave={component.setMouseOver.bind(component, false)}
                onMouseOver={component.setMouseOver.bind(component, true)}
                onClick={component.clickInPosition.bind(component)}
            >
                {
                    this.displayCellText()
                }
            </div>
        )
    }
}

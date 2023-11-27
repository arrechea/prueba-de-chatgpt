import React from 'react'

export default class MapDetails extends React.Component {
    static get defaultProps() {
        return {
            meeting: null,
            lang: null,
            positions: null,
        }
    }

    /**
     * First public
     * @returns {Array}
     */
    getOrderedPositions() {
        let {positions} = this.props;
        let response = [];
        for (let positionId in positions) {
            if (positions.hasOwnProperty(positionId)) {
                let position = positions[positionId];
                if (position.type === 'public') {
                    response.push(position);
                } else {
                    // response.push(position);
                }
            }
        }

        return response;
    }

    render() {
        let {lang} = this.props;
        let positionsOrdered = this.getOrderedPositions();

        return (
            <div className="MapDetails">
                {positionsOrdered.map((position) => {
                    let isPublic = position.type === 'public';
                    if (!isPublic) {
                        return null;
                    }
                    return (
                        <div
                            className="MapDetails--position"
                            key={`MapDetails--position--${position.id}`}
                        >
                            <div className="MapDetails--position--list">
                                <div className="MapDetails--position--list--data">
                                    <div className="MapDetails--position--list--data-image enabled">
                                        <img src={position.image} alt=""/>
                                    </div>

                                    <span className="MapDetails--position--list--data--name">
                                        { isPublic ? lang['meeting.map.position.enabled'] : position.name }
                                    </span>
                                </div>
                                {/*<div className="MapDetails--position--list--data"*/}
                                     {/*key={`MapDetails--position--${position.id}--selected`}>*/}
                                    {/*<img src={position.image_selected} alt=""/>*/}
                                    {/*<span*/}
                                        {/*className="MapDetails--position--list--data--name">{lang['meeting.map.position.selected']}</span>*/}
                                {/*</div>*/}
                                <div className="MapDetails--position--list--data"
                                     key={`MapDetails--position--${position.id}--disabled`}>
                                    <div className="MapDetails--position--list--data-image disabled">
                                        <img src={position.image_disabled} alt=""/>
                                    </div>
                                    <span
                                        className="MapDetails--position--list--data--name">{lang['meeting.map.position.disabled']}</span>
                                </div>
                            </div>
                        </div>
                    )
                })}
            </div>
        )
    }
}

import React from 'react'

export default class GridButtons extends React.Component {
    static get defaultProps() {
        return {
            triggerPlus: () => {
            },
            triggerMinus: () => {
            }
        }
    }

    render() {
        return (
            <div className="GridButtons">
                <div className="GridButtons--plus" onClick={this.props.triggerPlus}>
                    <i className="material-icons">add</i>
                </div>
                <div className="GridButtons--minus" onClick={this.props.triggerMinus}>
                    <i className="material-icons">remove</i>
                </div>
            </div>
        )
    }
}

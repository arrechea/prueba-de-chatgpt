import React from 'react'

export default class Button extends React.Component {
    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            text,
            onClick,
            type,
            background,
            color,
        } = this.props;
        if (!background) {
            background = 'white';
        }
        if (!color) {
            color = 'black';
        }

        return (
            <button
                className={`WidgetBUQ--button WidgetBUQ--button--${type}`}
                onClick={onClick}
                style={{
                    backgroundColor: background,
                    color: color
                }}
            >
                {text}
            </button>
        )
    }
}

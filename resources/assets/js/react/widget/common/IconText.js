import React from 'react'

export default class IconText extends React.Component {
    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            text,
            icon,
            onClick
        } = this.props;

        return (
            <div
                className="WidgetBUQ--IconText"
                onClick={() => {
                    if (onClick) {
                        onClick();
                    }
                }}
            >
                <div className="WidgetBUQ--IconText--icon">
                    {icon}
                </div>
                <div className="WidgetBUQ--IconText--text">
                    {text}
                </div>
            </div>
        )
    }
}

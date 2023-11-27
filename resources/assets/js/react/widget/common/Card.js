import React from 'react'

export default class Card extends React.Component {
    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            title,
            subtitle,
            right,
            onClick
        } = this.props;

        return (
            <div
                className="WidgetBUQ--Card"
                onClick={onClick}
            >
                <div className="WidgetBUQ--Card--left">
                    <div className="WidgetBUQ--Card--title">{title}</div>
                    {subtitle ? (
                        <div className="WidgetBUQ--Card--subtitle">{subtitle}</div>
                    ) : null}
                </div>
                <div className="WidgetBUQ--Card--right">
                    {right}
                </div>
            </div>
        )
    }
}

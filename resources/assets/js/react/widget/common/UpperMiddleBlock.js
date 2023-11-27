import React from 'react'
import StoreWidget from "../StoreWidget";
export default class UpperMiddleBlock extends React.Component {
    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            children,
        } = this.props;
        return (
            <div
                className="WidgetBUQ--UpperMiddleBlock"
                style={{
                    backgroundColor: StoreWidget.color,
                }}
            >{children}</div>
        )
    }
}

import React from 'react'
import StoreWidget from "../StoreWidget";
import IconText from "./IconText";
export default class UpperBlock extends React.Component {
    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            children,
            icon
        } = this.props;
        return (
            <div className="WidgetBUQ--UpperBlock"
                 style={{
                     backgroundColor: StoreWidget.color,
                 }}
            >
                <IconText
                    icon={icon}
                    text={children}
                />
            </div>
        )
    }
}

import React from 'react'
export class Tabs extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            activeTabIndex: this.props.defaultActiveTabIndex
        };
        this.handleTabClick = this.handleTabClick.bind(this);
    }

    static get defaultProps() {
        return {
            selectTab: () => {
            },
            defaultActiveTabIndex: 0,
        }
    }

    // Toggle currently active tab
    handleTabClick(tabIndex, tabId) {
        this.setState({
            activeTabIndex: tabIndex === this.state.activeTabIndex ? this.props.defaultActiveTabIndex : tabIndex
        }, () => {
            this.props.selectTab(tabId)
        });
    }

    // Encapsulate <Tabs/> component API as props for <Tab/> children
    renderChildrenWithTabsApiAsProps() {
        return React.Children.map(this.props.children, (child, index) => {
            return React.cloneElement(child, {
                onClick: this.handleTabClick,
                tabIndex: index,
                isActive: index === this.state.activeTabIndex
            });
        });
    }

    // Render current active tab content
    renderActiveTabContent() {
        const {children} = this.props;
        const {activeTabIndex} = this.state;
        if (children[activeTabIndex]) {
            return children[activeTabIndex].props.children;
        }
    }

    render() {
        return (
            <div className="TabsGafaFit">
                <ul className="TabsGafaFit--menu">
                    {this.renderChildrenWithTabsApiAsProps()}
                </ul>
                <div className="TabsGafaFit-content">
                    {this.renderActiveTabContent()}
                </div>
            </div>
        );
    }
}

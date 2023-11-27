import React from 'react'
import GridMapGenerator from "./grid/GridMapGenerator";
import MenuMapGenerator from "./menu/MenuMapGenerator";

export default class AppGenerateMap extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount() {
        this.setState({
            loading: false
        })
    }

    render() {
        if (this.state.loading) {
            return (
                <div className="BuySystemStep" dangerouslySetInnerHTML={{__html: window.getLoadingImage()}}/>
            )
        }
        return (
            <div className="AppGenerateMap">
                <MenuMapGenerator/>
                <GridMapGenerator/>
            </div>
        )
    }
}

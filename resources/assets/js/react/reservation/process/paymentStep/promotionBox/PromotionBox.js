import React from 'react'
import TransformToGiftCard from "./TransformToGiftCard";
import AddGiftCard from "./AddGiftCard";

export default class PromotionBox extends React.Component {
    render() {
        let style = (this.props.cart.length > 0  && this.props.total === 0 )?{ display:'none'}:{};

        return (
            <div className="gs-PromotionBox" style={style}>
                <TransformToGiftCard/>
                <AddGiftCard cart={this.props.cart} discount={ this.props.discount } />
            </div>
        )
    }
}

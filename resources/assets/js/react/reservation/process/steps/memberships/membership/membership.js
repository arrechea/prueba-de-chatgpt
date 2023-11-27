import React, {Component} from 'react';
import ProductPrice from "../../../selectProductsStep/ProductPrice";
import StoreReservation from "../../../StoreReservation";
import IconMinus from "../../../ui/IconMinus";
import IconPlus from "../../../ui/IconPlus";
// import "./membership.css";

class Membership extends Component{
	constructor(props){
		super(props);
		this.state = {
			amount: 1
		}

		this.handleAmount = this.handleAmount.bind(this);
		this.handleAddToCard = this.handleAddToCard.bind(this);
	}

	handleAmount(action = "add"){
		(action == "add") ? this.setState({ amount: this.state.amount + 1 }) : (this.state.amount < 2) ? this.setState({ amount: 1 }) : this.setState({ amount: this.state.amount - 1 })
	}

	handleAddToCard(){
		let membership = this.props.membershipData;
		this.props.membershipsHandleCart(Object.assign({ amount: this.state.amount, type: 'membership', product_type:"App\\Models\\Membership\\Membership" }, membership));
	}

	render(){
		return(
			<div className="GfStore__ProductsItems">
                <div className="product">
                    <h3>{this.props.membershipData.name}</h3>
                    <p>{this.props.membershipData.short_description}</p>
                    <span className="value"><ProductPrice product={this.props.membershipData} /></span>
                </div>
                <div className="amount">
                    <button className="minus" onClick={() => this.handleAmount("minus")} >
						<IconMinus />
					</button>
					<span>{this.state.amount}</span>
					<button onClick={() => this.handleAmount()} className="plus">
						<IconPlus />
					</button>
                </div>
                <button onClick={this.handleAddToCard} className="add-to-card">AGREGAR</button>
            </div>
		)
	}
}

export default Membership;

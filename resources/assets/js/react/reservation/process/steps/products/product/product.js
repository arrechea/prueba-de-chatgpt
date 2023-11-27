import React, {Component} from 'react';
import IconMinus from "../../../ui/IconMinus";
import IconPlus from "../../../ui/IconPlus";
import StoreReservation from "../../../StoreReservation";

class Product extends Component{
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
		let product = this.props.productData;
		this.props.productsHandleCart(Object.assign({ amount: this.state.amount, type: 'product', price_final: this.props.productData.price, product_type:"App\\Models\\Products\\Product" }, product));
	}

	render(){
        let currency = StoreReservation.get('currency');
		return(
			<div className="GfStore__ProductsItems">
                <div className="product">
                    <h3>{this.props.productData.name}</h3>
                    <p>{this.props.productData.short_description}</p>
                    <span className="value">{currency.prefijo}{this.props.productData.price}</span>
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

export default Product;

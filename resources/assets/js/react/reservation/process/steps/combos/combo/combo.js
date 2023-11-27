import React, {Component} from 'react';
import ProductPrice from "../../../selectProductsStep/ProductPrice";
import StoreReservation from "../../../StoreReservation";
import IconMinus from "../../../ui/IconMinus";
import IconPlus from "../../../ui/IconPlus";
// import "./combo.css";

class Combo extends Component {
    constructor(props) {
        super(props);
        this.state = {
            amount: 1,
            neccesaryCredits: null,
            userCredits: null
        }

        this.handleAmount = this.handleAmount.bind(this);
        this.handleAddToCard = this.handleAddToCard.bind(this);
    }

    componentDidMount() {
        let neccesaryCredits = StoreReservation.get("meeting_neccesaryCredits");
        let userCredits = StoreReservation.get("user_Credits");
        this.setState({neccesaryCredits: neccesaryCredits, userCredits: userCredits});
    }

    handleAmount(action = "add") {
        (action == "add") ? this.setState({amount: this.state.amount + 1}) : (this.state.amount < 2) ? this.setState({amount: 1}) : this.setState({amount: this.state.amount - 1})
    }

    handleAddToCard() {
        let combo = this.props.comboData;
        this.props.combosHandleCart(Object.assign({
            amount: this.state.amount,
            type: 'combo',
            product_type: "App\\Models\\Combos\\Combos"
        }, combo));
    }

    getPositionsSelecteds() {
        let map_objectsSelected = StoreReservation.get('map_objectsSelected');
        if (Array.isArray(map_objectsSelected)) {
            return map_objectsSelected.length;
        }
        return 1;
    }

    getNeccesaryCredits() {
        let neccesaryCredits = StoreReservation.get('meeting_neccesaryCredits');

        let numberOfPositions = this.getPositionsSelecteds();

        if (numberOfPositions <= 1) {
            return neccesaryCredits;
        }
        let firstNeccesary = neccesaryCredits[0];

        return new Array((numberOfPositions * neccesaryCredits.length)).fill(firstNeccesary);
    }

    /**
     *
     * @param combo
     * @param neccesaryCredits
     * @param user_Credits
     * @returns {boolean}
     */
    comboIsFiltered(combo, neccesaryCredits, user_Credits) {

        if (neccesaryCredits && neccesaryCredits.length) {
            //Datos que entrega
            let comboCreditId = combo.credits_id;
            let comboCredits = combo.credits;

            //Buscamos creditos necesarios
            let creditosNecesarios = neccesaryCredits.length;
            let creditoComparable = neccesaryCredits.filter(function (credito) {
                return credito.id === comboCreditId;
            }).slice().shift();

            if (creditoComparable && creditoComparable.services && creditoComparable.services.length) {
                //Hay datos de servicio
                let servicio = creditoComparable.services.slice().shift();
                creditosNecesarios = parseInt(servicio.pivot.credits) * this.getPositionsSelecteds();
            }

            //Buscamos creditos que tenga el usuario
            let creditosUser = user_Credits.filter(function (creditoUser) {
                return creditoUser.credits_id === comboCreditId;
            }).length;

            let esValido = (creditosUser + comboCredits) >= creditosNecesarios;

            // console.warn(
            //     'ID credito:', comboCreditId,
            //     '\ncantidad:', comboCredits,
            //     // '\ncreditoComparable:', creditoComparable,
            //     '\ncreditosUser:', creditosUser,
            //     '\ncreditosNecesarios:', creditosNecesarios,
            //     '\nPasa:', (esValido ? 'Si' : 'No')
            // );
            if (!esValido) {
                //No se alcanza la cantidad necesaria
                return true;
            }
        }
        return false;
    }

    render() {
        let neccesaryCredits = StoreReservation.get('meeting_neccesaryCredits');
        let user_Credits = StoreReservation.get('user_Credits');

        if (this.comboIsFiltered(this.props.comboData, neccesaryCredits, user_Credits)) {
            return null;
        }
        return (
            <div className="GfStore__ProductsItems">
                <div className="product">
                    <h3>{this.props.comboData.name}</h3>
                    <p>{this.props.comboData.short_description}</p>
                    <span className="value"><ProductPrice product={this.props.comboData}/></span>
                </div>
                <div className="amount">
                    <button className="minus" onClick={() => this.handleAmount("minus")}>
                        <IconMinus />
                    </button>
                    <span>{this.state.amount}</span>
                    <button className="plus" onClick={() => this.handleAmount()}>
                        <IconPlus />
                    </button>
                </div>
                <button onClick={this.handleAddToCard} className="add-to-card">AGREGAR</button>
            </div>
        )
    }
}

export default Combo;

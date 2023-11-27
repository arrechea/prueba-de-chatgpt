import React, {Component} from 'react';
import StoreReservation from "../../StoreReservation";
import Combo from "./combo/combo";
import Slider from "react-slick";

// import "./combos.css";

class Combos extends Component {
    constructor(props) {
        super(props);
        this.state = {
            combos: StoreReservation.get("combosSelection"),
            searchString: null
        };

        this.handleFilter = this.handleFilter.bind(this);
    }

    handleFilter(e) {
        let filterElements = this.state.combos.filter(combo => {
            return combo.name.toLowerCase().includes(e.target.value.toLowerCase());
        });
        (e.target.value.length > 0) ? this.setState({
            searchString: e.target.value,
            combos: filterElements
        }) : this.setState({searchString: null, combos: StoreReservation.get("combosSelection")});
    }

    render() {


        var settings = {
            dots: true,
            infinite: false,
            speed: 500,
            slidesToShow: 3,
            slidesToScroll: 3,
            rows: 1,
            responsive: [
                {

                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        dots: false
                    },
                },
            ]
        };

        if (this.state.searchString != null) {
            settings.slidesToShow = this.state.combos.length;
            settings.slidesToScroll = this.state.combos.length;
        }

        let {combos} = this.state;


        let {admin, handleCart} = this.props;

        let filteredCombos = combos.filter(function (combo) {
            let neccesaryCredits = StoreReservation.get('meeting_neccesaryCredits');
            let user_Credits = StoreReservation.get('user_Credits');
            return !StoreReservation.comboIsFiltered(combo, neccesaryCredits, user_Credits) && (admin || (!admin && !combo.hide_in_front));
        });

        let comboList;

        // if (admin === null) {
        //     comboList = combos.map(function (combo) {
        //         if (!combo.hide_in_front) {
        //             return <Combo key={combo.id} comboData={combo} combosHandleCart={handleCart}/>
        //         }
        //     });
        // } else {
        //     comboList = combos.map(function (combo, index) {
        //         return <Combo key={combo.id} comboData={combo} combosHandleCart={handleCart}/>
        //     })
        // }
        comboList = filteredCombos.map(function (combo, index) {
            return <Combo key={combo.id} comboData={combo} combosHandleCart={handleCart}/>
        });

        let {active} = this.props;

        return (
            <div id="combos" className={`storeItemContent ${active ? 'active' : 'd-none'}`} key={'ReservationFancy--TabContent--combos'}>
                <div className="product-search-container">
                    <form className="search-products" action="#">
                        <input type="text" onKeyUp={this.handleFilter} placeholder="Buscar paquetes"/>
                    </form>
                </div>
                <div className="products-container">
                    <Slider {...settings}>
                        {comboList}
                    </Slider>
                </div>
            </div>
        )
    }
}

export default Combos;

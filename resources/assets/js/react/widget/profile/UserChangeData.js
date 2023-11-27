import React from 'react'
import StoreReservation from "../../reservation/process/StoreReservation";
import Tab from "react-bootstrap/Tab";
import Tabs from "react-bootstrap/Tabs";
import Nav from "react-bootstrap/Nav";
import IconText from "../common/IconText";
import moment from 'moment';
import {isFunction} from "../utils/TypeUtils";
import 'moment/locale/es';
import UserFormData from "./UserForm/UserFormData";
import UserAddressData from './UserForm/UserAddressData';
import UserContactData from './UserForm/UserContactData';
import ProfileElements from '../helpers/ProfileElements';
import StoreWidget from '../StoreWidget';
// import Form from "react-bootstrap/Form";

export default class UserChangeData extends React.Component{
   constructor(props) {
      super(props);
      this.state = {
         email: "",
         first_name: "",
         last_name: "",
         // birthDate: new Date(),
         birth_date: "",
         password: "",
         password_confirmation: "",
         address: "",
         internal_number: "",
         external_number: "",
         municipality: "",
         postal_code: "",
         city: "",
         countries_id: "",
         country_states_id: "",
         countries: [],
         states: [],
         phone: "",
         cel_phone: "",
         gender: "",
         formErrors: {first_name: ''},
         first_nameValid: true,
         formValid: true,
         serverError: '',
         saved: false,
         changed: StoreWidget.information_changed,
      };

      StoreWidget.addSegmentedListener('information_changed', this.updateChanged.bind(this));
   }

   componentDidMount() {
      const currentComponent = this;
      let user = StoreReservation.get('user');

      this.setState({
         email: user.email,
         first_name: user.first_name,
         last_name: user.last_name,
         // birthDate: new Date(user.birth_date.substring(0, 11)),
         birth_date: moment(user.birth_date).format('YYYY-MM-DD'),
         address: user.address,
         internal_number: user.internal_number,
         external_number: user.external_number,
         municipality: user.municipality,
         postal_code: user.postal_code,
         city: user.city,
         countries_id: user.countries_id,
         country_states_id: user.country_states_id,
         phone: user.phone,
         cel_phone: user.cel_phone,
         gender: user.gender,
      });
      currentComponent.getCountryList(currentComponent.getStatesListByCountry.bind(currentComponent));
   }

   findCountryCodeById() {
      let country = this.state.countries.find(option => option.value === this.state.countries_id);
      let countryCode = "";
      if (country != null) {
         countryCode = country.code;
      }
      return countryCode;
   }

   getCountryList(callback) {
      const currentComponent = this;
      ProfileElements.getCountries(function (result) {
         currentComponent.setState({
            countries: result.map(function (item) {
               return {label: item.name, value: item.id, code: item.code}
            }),
         });
         if (isFunction(callback)) callback();
      });
   }

   getStatesListByCountry(callback) {
      const currentComponent = this;
      let countrySelected = currentComponent.findCountryCodeById();
      ProfileElements.getCountryStates(countrySelected, function (result) {
         currentComponent.setState({
               states: result.map(function (item) {
                  return {label: item.name, value: item.id}
               })
            });
         if (isFunction(callback)) callback();
      });
   }

   updateState(state) {
      this.setState(state);
   }

   updateChanged(){
      let changedStore = StoreWidget.information_changed;

      this.setState({
         changed: changedStore,
      });
   }

   handleChangeField(event) {
      let fieldName = event.target.id;
      let fieldValue = event.target.value;
      this.setState({
          [fieldName]: fieldValue,
      });

      StoreWidget.set('information_changed', true)
   }

   handleSubmit(event) {
      event.preventDefault();
      let currentElement = this;
      currentElement.setState({serverError: '', saved: false});

      ProfileElements.putMe(this.state,
         currentElement.successSaveMeCallback.bind(this),
         currentElement.errorSaveMeCallback.bind(this)
      );
   }

   successSaveMeCallback(result) {
      this.setState({
         saved: true
      });
      
      StoreWidget.savedMe(true, null);
      
      if (this.props.successCallback) {
         this.props.successCallback(result);
      }
   }
   
   errorSaveMeCallback(error) {
      console.log(error);
      this.setState({
         serverError: error
      });
   }


   render(){

      let {changed} = this.state;

      return(
         <div className={'WidgetBUQ--UserChangeData'}>
            <Tab.Container defaultActiveKey="Usuario">
               <Nav className="WidgetBUQ--tabs">
                  <Nav.Item className="WidgetBUQ--tabs-item">
                     <Nav.Link eventKey="Usuario">
                        <IconText
                           text="Usuario"
                           icon={null}
                        /> 
                     </Nav.Link>
                  </Nav.Item>
                  <Nav.Item className="WidgetBUQ--tabs-item">
                     <Nav.Link eventKey="Dirección">
                        <IconText
                           text="Dirección"
                           icon={null}
                        />
                     </Nav.Link>
                  </Nav.Item>
                  <Nav.Item className="WidgetBUQ--tabs-item">
                     <Nav.Link eventKey="Contacto">
                     <IconText
                           text="Contacto"
                           icon={null}
                        />
                     </Nav.Link>
                  </Nav.Item>
               </Nav>
               <Tab.Content className="WidgetBUQ--UserChangeData-content">
                  <form onSubmit={this.handleSubmit.bind(this)}>
                     <Tab.Pane className="WidgetBUQ--tab" eventKey="Usuario">
                        <UserFormData 
                           userData={this.state} 
                           updateState={this.updateState.bind(this)}
                           handleChangeField={this.handleChangeField.bind(this)}
                        />
                     </Tab.Pane>
                     <Tab.Pane className="WidgetBUQ--tab" eventKey="Dirección">
                        <UserAddressData
                           userData={this.state}
                           updateState={this.updateState.bind(this)}
                           getStatesListByCountry={this.getStatesListByCountry.bind(this)}
                           handleChangeField={this.handleChangeField.bind(this)}
                           />
                     </Tab.Pane>
                     <Tab.Pane className="WidgetBUQ--tab" eventKey="Contacto">
                        <UserContactData 
                           userData={this.state}
                           handleChangeField={this.handleChangeField.bind(this)}
                        />
                     </Tab.Pane>

                     <button className={'WidgetBUQ--SubmitButton ' + (changed ? ' is-active' : '')} disabled={!this.state.formValid} type="submit">
                        Guardar
                     </button>
                  </form>
               </Tab.Content>
            </Tab.Container>
         </div>
      )
   }

}
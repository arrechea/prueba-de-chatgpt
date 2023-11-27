import React from 'react';
import Form from "react-bootstrap/Form";
import Select from "react-select";

export default class UserAddressData extends React.Component{
   constructor(props) {
      super(props);
   }

   handleChangeCountry(selectedOption) {
      this.props.userData.countries_id = selectedOption.value;
      this.props.getStatesListByCountry();
      this.props.updateState(this.props.userData);
   }

   handleChangeState(selectedOption) {
      this.props.userData.country_states_id = selectedOption.value;
      this.props.updateState(this.props.userData);
   }

   
   render(){
      let {userData, handleChangeField} = this.props;
      let address = userData.address ? userData.address : '' ;
      let external_number = userData.external_number ? userData.external_number : '' ;
      let internal_number = userData.internal_number ? userData.internal_number : '' ;
      let postal_code = userData.postal_code ? userData.postal_code : '' ;
      let municipality = userData.municipality ? userData.municipality : '' ;
      let city = userData.city ? userData.city : '' ;

      return(
         <div className={'WidgetBUQ--UserAddressData'}>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="address">
               <Form.Label>Dirección</Form.Label>
               <Form.Control
                  autoFocus
                  type="text"
                  placeholder="ej. name@ejemplo.com"
                  value={address}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <div className={'WidgetBUQ--form-col2'}>
               <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="external_number">
                  <Form.Label>No. Exterior</Form.Label>
                  <Form.Control
                     autoFocus
                     type="number"
                     value={external_number}
                     onChange={handleChangeField}
                  />
               </Form.Group>
               <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="internal_number">
                  <Form.Label>No. Interior</Form.Label>
                  <Form.Control
                     autoFocus
                     type="number"
                     value={internal_number}
                     onChange={handleChangeField}
                  />
               </Form.Group>
            </div>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="postal_code">
               <Form.Label>Código Postal</Form.Label>
               <Form.Control
                  autoFocus
                  type="number"
                  value={postal_code}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="municipality">
               <Form.Label>Municipio</Form.Label>
               <Form.Control
                  autoFocus
                  type="text"
                  value={municipality}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="city">
               <Form.Label>Ciudad</Form.Label>
               <Form.Control
                  autoFocus
                  type="text"
                  value={city}
                  onChange={handleChangeField}
               />
            </Form.Group>

            <div className={'WidgetBUQ--select-group'}>
               <Form.Label>País</Form.Label>
               <Select 
                  options={userData.countries}
                  placeholder={'Seleccionar'}
                  className={'WidgetBUQ--select'}
                  classNamePrefix={'WidgetBUQ--select'}
                  // menuIsOpen={true}
                  value={userData.countries.find(option => option.value === userData.countries_id)}
                  onChange={this.handleChangeCountry.bind(this)}
               />
            </div>

            <div className={'WidgetBUQ--select-group'}>
               <Form.Label>Estado</Form.Label>
               <Select 
                  options={userData.states}
                  placeholder={'Seleccionar'}
                  className={'WidgetBUQ--select'}
                  classNamePrefix={'WidgetBUQ--select'}
                  value={userData.states.find(option => option.value === userData.country_states_id)}
                  onChange={this.handleChangeState.bind(this)}
               />
            </div>
         </div>
      )
   }
}
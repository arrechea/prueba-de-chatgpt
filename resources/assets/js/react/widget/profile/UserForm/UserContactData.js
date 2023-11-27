import React from 'react';
import Form from "react-bootstrap/Form";
import Select from "react-select";

export default class UserContactData extends React.Component{
   constructor(props) {
      super(props);
   }

   render(){
      let {userData, handleChangeField} = this.props;
      let phone = userData.phone ? userData.phone : '';
      let cel_phone= userData.cel_phone ? userData.cel_phone : '';

      return(
         <div className={'WidgetBUQ--UserContactData'}>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="email">
               <Form.Label>Correo electrónico</Form.Label>
               <Form.Control
                  autoFocus
                  disabled
                  type="text"
                  placeholder="ej. name@ejemplo.com"
                  value={userData.email}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="phone">
               <Form.Label>Teléfono</Form.Label>
               <Form.Control
                  autoFocus
                  type="number"
                  value={phone}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="cel_phone">
               <Form.Label>Móvil</Form.Label>
               <Form.Control
                  autoFocus
                  type="text"
                  value={cel_phone}
                  onChange={handleChangeField}
               />
            </Form.Group>
         </div>
      )
   }
}
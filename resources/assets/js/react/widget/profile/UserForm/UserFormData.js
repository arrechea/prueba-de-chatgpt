import React from 'react';
import moment from 'moment';
import 'moment/locale/es';
import DatePicker from 'react-date-picker';
import Form from "react-bootstrap/Form";

export default class UserFormData extends React.Component{

   constructor(props) {
      super(props);
   }

   validateFirstName(fieldName, value) {
      let fieldValidationErrors = this.props.userData.formErrors;
      let first_nameValid = value !== '' && value !== null;
      fieldValidationErrors.first_name = first_nameValid ? '' : 'Error de nombre';
      this.props.userData.formErrors = fieldValidationErrors;
      this.props.userData.first_nameValid = first_nameValid;
      this.validateForm();
   }

   validateForm() {
      this.props.userData.formValid = this.props.userData.first_nameValid;
      this.props.updateState(this.props.userData);
   }

   handleChangeFirstName(event){
      let fieldName = event.target.id;
      let fieldValue = event.target.value;
      this.props.userData[fieldName] = fieldValue;
      this.validateFirstName(fieldName, fieldValue);
      this.props.updateState(this.props.userData);
   }

   handleChangeGender(event){
      this.props.userData.gender = event.target.value;
      this.props.updateState(this.props.userData);
   }

   handleChangeBirthDate(date){
      const dateFormatted = moment(date).format('YYYY-MM-DD');
      this.props.userData.birth_date = dateFormatted;
      this.props.updateState(this.props.userData);
   }
   
   render(){
      let {userData, handleChangeField} = this.props;
      let first_name = userData.first_name === null ? '' : userData.first_name;
      let last_name = userData.last_name === null ? '' : userData.last_name;
      let birth_date = userData.birth_date === null ? moment().toDate() : moment(userData.birth_date).toDate();

      return(
         <div className={'WidgetBUQ--UserFormData'}>
            <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="first_name">
               <Form.Label>Nombre(s)</Form.Label>
               <Form.Control
                  autoFocus
                  type="text"
                  value={userData.first_name}
                  onChange={this.handleChangeFirstName.bind(this)}
               />
            </Form.Group>
            <Form.Group  bsPrefix={'WidgetBUQ--form-group'} controlId="last_name">
               <Form.Label>Apellido(s)</Form.Label>
               <Form.Control
                  type="text"
                  value={userData.last_name}
                  onChange={handleChangeField}
               />
            </Form.Group>
            <div className={'WidgetBUQ--checkbox-group'}>
               <Form.Check 
                  name="gender"
                  className={'WidgetBUQ--form-check'}
                  type={'radio'}
                  value={"male"}
                  label={'Hombre'}
                  checked={userData.gender === "male"}
                  onChange={this.handleChangeGender.bind(this)}
               />
               <Form.Check 
                  name="gender"
                  className={'WidgetBUQ--form-check'}
                  type={'radio'}
                  value={"female"}
                  label={'Mujer'}
                  checked={userData.gender === "female"}
                  onChange={this.handleChangeGender.bind(this)}
               />
            </div>
            <div className={'WidgetBUQ--form-group'}>
               <Form.Label>Fecha de nacimiento</Form.Label>
               <DatePicker
                  value={userData.birth_date ? birth_date : moment().toDate()}
                  onChange={this.handleChangeBirthDate.bind(this)}
                  clearIcon={null}
                  calendarIcon={null}
                  maxDate={moment().toDate()}
               />
            </div>
         </div>
      )
   }
}
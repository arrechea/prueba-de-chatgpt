import React from 'react'
import StoreWidget from '../StoreWidget'
import LoginStepTemplate from "../../reservation/process/steps/LoginStepTemplate";
import StoreReservation from "../../reservation/process/StoreReservation";
import ProfileElements from "../helpers/ProfileElements";
import Tab from "react-bootstrap/Tab";
import Tabs from "react-bootstrap/Tabs";
import Nav from "react-bootstrap/Nav";
import IconText from "../common/IconText";
import Notification from "../common/Notification";
import ShoppingList from './ShoppingList';
import UserChangeData from './UserChangeData';
import PaymentMethods from './PaymentMethods';
export default class Profile extends React.Component {
   constructor() {
      super();

      let user = StoreReservation.get('user');
      if (!user) {
         StoreWidget.set('step', <LoginStepTemplate next={<Profile/>} store={StoreWidget}/>);
      }
   }

   componentDidMount() {
      StoreWidget.set('backholder', 'none');
   }

   render() {
      let user = StoreReservation.get('user');

      if (!user) {
         return null;
      } else {
         return (
            <div className="WidgetBUQ--Profile" >
               <div className="WidgetBUQ--UpperBlock">
                  <a href="#" className="WidgetBUQ--linkGo is-white">
                     <i className="fal fa-chevron-left"></i>
                     <span>Reservar mi clase</span>
                  </a>
                  <div className="WidgetBUQ--UserData">
                     <div className="WidgetBUQ--UserData-title">
                        <img />
                        <h3>¡Hola {user.first_name}<br></br>
                           Bienvenido
                        </h3>
                     </div>

                     <div className="WidgetBUQ--UserData-wallet">
                        <div className="WidgetBUQ--UserData-credits">
                           <p>12</p>   
                        </div>
                        <div className="WidgetBUQ--UserData-expire">
                           <h5>Créditos disponibles</h5>   
                           <p>Expira. 09/sep/2019</p>   
                        </div>
                        <div className="WidgetBUQ--UserData-goLink">
                           <IconText
                              text=""
                              icon={<i className="far fa-chevron-right"></i>}
                           /> 
                        </div>
                     </div>
                  </div>
               </div>


               <div className="WidgetBUQ--Profile-container">
                  <Tab.Container defaultActiveKey="Mis Compras">
                     <Nav className="WidgetBUQ--tabs">
                        <Nav.Item className="WidgetBUQ--tabs-item">
                           <Nav.Link eventKey="Mis Compras">
                              <IconText
                                 text="Mis Compras"
                                 icon={<i className="fal fa-shopping-basket"></i>}
                              /> 
                           </Nav.Link>
                        </Nav.Item>
                        <Nav.Item className="WidgetBUQ--tabs-item">
                           <Nav.Link eventKey="Formas de Pago">
                              <IconText
                                 text="Formas de pago"
                                 icon={<i className="fal fa-credit-card"></i>}
                              />
                           </Nav.Link>
                        </Nav.Item>
                        <Nav.Item className="WidgetBUQ--tabs-item">
                           <Nav.Link eventKey="Mis Datos">
                           <IconText
                                 text="Mis datos"
                                 icon={<i className="fal fa-user"></i>}
                              />
                           </Nav.Link>
                        </Nav.Item>
                     </Nav>
                     <Tab.Content className="WidgetBUQ--Profile-content">
                        <Tab.Pane className="WidgetBUQ--tab" eventKey="Mis Compras">
                           <ShoppingList />
                        </Tab.Pane>
                        <Tab.Pane className="WidgetBUQ--tab" eventKey="Formas de Pago">
                           <PaymentMethods />
                        </Tab.Pane>
                        <Tab.Pane className="WidgetBUQ--tab is-user" eventKey="Mis Datos">
                           <UserChangeData />
                        </Tab.Pane>

                        <Notification />
                     </Tab.Content>
                  </Tab.Container>
               </div>
            </div>
         )
      }
   }
}

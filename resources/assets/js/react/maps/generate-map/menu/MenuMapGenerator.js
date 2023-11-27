import React from 'react'
import StoreMapGenerator from "../StoreMapGenerator";
import PositionSelectorMapGenerator from "./PositionSelectorMapGenerator";

export default class MenuMapGenerator extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            room_title: StoreMapGenerator.get('room_title'),
            room_image: StoreMapGenerator.get('room_image'),
            search: StoreMapGenerator.get('search'),
            loading: false,
            errores: null,
        };
        StoreMapGenerator.addListener(this.ListenerStore.bind(this));
    }

    /**
     * Listener
     * @constructor
     */
    ListenerStore() {
        this.setState({
            room_title: StoreMapGenerator.get('room_title'),
            room_image: StoreMapGenerator.get('room_image'),
            search: StoreMapGenerator.get('search'),
        })
    }

    ChangeBackground() {
        let imageInput = this.refs.room_image;
        let file = imageInput.files[0];

        let reader = new FileReader();

        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            StoreMapGenerator.set('room_image', reader.result)
        };

        if (file) {
            // read the image file as a data URL.
            reader.readAsDataURL(file);
        } else {
            StoreMapGenerator.set('room_image', '')
        }
    }

    DeleteBackground() {
        let imageInput = this.refs.room_image;
        imageInput.value = '';
        StoreMapGenerator.set('room_image', '');
    }

    /**
     * Change title
     * @constructor
     */
    ChangeTitle() {
        StoreMapGenerator.set('room_title', this.refs.room_title.value);
    }

    ChangeActive(e) {
        StoreMapGenerator.set('room_active', this.refs.room_active.checked);
    }

    SearchPositions() {
        StoreMapGenerator.set('search', this.refs.search.value);
    }

    ResetMap() {
        let imageInput = this.refs.room_image;
        imageInput.value = '';
        StoreMapGenerator.resetMap();
    }

    isLoading() {
        return this.state.loading === true;
    }

    clearErrors() {
        this.setState({
            errores: null
        });
    }

    Save(forze) {
        let lang = StoreMapGenerator.get('lang');
        let urlForm = StoreMapGenerator.get('urlForm');
        let numberOfPositions = StoreMapGenerator.getPositionCount('public');
        let component = this;

        if (numberOfPositions < 1) {
            //If is not any position
            component.setState({
                errores: (
                    <div>{lang['error.notPositions.public']}</div>
                ),
            });
            return null;
        }
        if (forze !== true) {
            let numberOfPrivatePositions = StoreMapGenerator.getPositionCount('private');
            let numberOfCoachPositions = StoreMapGenerator.getPositionCount('coach');
            if ((numberOfPrivatePositions + numberOfCoachPositions) < 1) {
                component.setState({
                    errores: (
                        <div>
                            {lang['error.notPositions.private']}
                            <br/>
                            <button className="btn btn-small" onClick={component.Save.bind(component, true)} style={{
                                marginLeft: 2
                            }}>
                                {lang['error.notPositions.private.button.yes']}
                            </button>
                            <button className="btn btn-small" onClick={component.clearErrors.bind(this)} style={{
                                marginLeft: 2
                            }}>
                                {lang['error.notPositions.private.button.no']}
                            </button>
                        </div>
                    ),
                });
                return null;
            }
        }


        if (urlForm) {
            if (component.isLoading()) {
                return;
            }
            let name = StoreMapGenerator.get('room_title');
            let imageInput = component.refs.room_image;
            let map = StoreMapGenerator.get('map');
            let active = StoreMapGenerator.get('room_active');

            let formData = new FormData();

            if (imageInput.files[0]) {
                //With file in input
                formData.append("image_background", imageInput.files[0]);
            } else if (StoreMapGenerator.room_image) {
                //With image in store
                formData.append("image_background", StoreMapGenerator.room_image);
            }

            formData.append("name", name);
            formData.append('map', JSON.stringify(map));
            formData.append("_token", Laravel.csrfToken);
            if (active) {
                formData.append('active', active);
            }

            component.setState({
                loading: true,
            }, () => {
                $.ajax({
                    url: urlForm,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function (data, textStatus, jqXHR) {
                        component.setState({
                            errores: (
                                <div>{lang['redirection']}</div>
                            ),
                        }, () => {
                            setTimeout(function () {
                                window.location.href = data;
                            }, 1000)
                        });
                    },
                    error: function (response) {
                        let errores = [];
                        try {

                            let messages = response.responseJSON.errors;

                            for (let errorSlug in messages) {
                                if (messages.hasOwnProperty(errorSlug)) {
                                    messages[errorSlug].forEach(function (text, index) {
                                        errores.push(
                                            <li key={`BuySystemStep--error-${errorSlug}--${index}`}>
                                                {text}
                                            </li>
                                        )
                                    });
                                }
                            }
                        } catch (e) {
                            errores = <div dangerouslySetInnerHTML={{__html: response.responseText}}/>;
                        }
                        component.setState({
                            loading: false,
                            errores: errores,
                        });
                    }
                });
            });
        }
    }

    loadingCircle() {
        return (
            <i className="material-icons small MenuMapGenerator--spinner">loop</i>
        );
    }

    render() {
        let lang = StoreMapGenerator.get('lang');
        return (
            <div className="MenuMapGenerator">
                {
                    this.state.errores !== null ?
                        (
                            <div className="MenuMapGenerator--errors alert alert-danger red-text">
                                <ul>
                                    {this.state.errores}
                                </ul>
                            </div>
                        )
                        :
                        null
                }
                <div className="MenuMapGenerator--Sup row">
                    <div className="col s1 m1">
                        <div className="MenuMapGenerator--reset" onClick={this.ResetMap.bind(this)}>
                            <i className="material-icons small">loop</i>
                        </div>
                    </div>
                    <div className="col s2 m1">
                        <div className="MenuMapGenerator--uploadPhoto">
                            <i className="material-icons small">add_a_photo</i>
                            <div className="MenuMapGenerator--uploadPhoto--tooltip">
                                <div className="btn btn-small">
                                    {lang['image.load']}
                                    <input type="file" onChange={this.ChangeBackground.bind(this)} ref="room_image"/>
                                </div>
                                <div className="btn btn-small" onClick={this.DeleteBackground.bind(this)}>
                                    {lang['image.delete']}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="col s7 m5 input-field">
                        <label htmlFor="">{lang['room_title']}<span className={'red-asterisk'}> *</span></label>
                        <input type="text" onChange={this.ChangeTitle.bind(this)} ref="room_title"
                               value={this.state.room_title ? this.state.room_title : ''}/>
                    </div>
                    <div className={'col s2'}>
                        <input id={'room_active'} type={'checkbox'} ref={'room_active'}
                               defaultChecked={StoreMapGenerator.get('room_active')}
                               onChange={this.ChangeActive.bind(this)}/>
                        <label htmlFor={'room_active'}>{lang['active']}</label>
                    </div>
                    {/*<div className="col s12 m3 input-field">*/}
                    {/*<label htmlFor="">{lang['search']}</label>*/}
                    {/*<input type="text" onChange={this.SearchPositions.bind(this)} ref="search"*/}
                    {/*value={this.state.search ? this.state.search : ''}/>*/}
                    {/*</div>*/}
                    <div className="col m1 s5 input-field">
                        <label htmlFor="">{lang['rows']}</label>
                        <input type="text" disabled={true} value={StoreMapGenerator.countRows()}/>
                    </div>
                    <div className="col m1 s5 input-field">
                        <label htmlFor="">{lang['columns']}</label>
                        <input type="text" disabled={true} value={StoreMapGenerator.countColumns()}/>
                    </div>
                    <div className="col m1 s2 MenuMapGenerator--reset">
                        <span className="" onClick={this.Save.bind(this)}>
                            {
                                this.isLoading() ?
                                    this.loadingCircle()
                                    :
                                    <i className="material-icons small">save</i>
                            }
                        </span>
                    </div>
                </div>
                <PositionSelectorMapGenerator/>
            </div>
        )
    }
}

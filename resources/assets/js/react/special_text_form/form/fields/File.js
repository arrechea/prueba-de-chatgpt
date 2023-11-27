import React from 'react';
import FieldBase from "./FieldBase";

export default class File extends FieldBase {

    constructor(props) {
        super(props);
        this.state = {
            fileName: this.GetValue(),
        }
    }

    /**
     *
     * @returns {string}
     */
    getNameFile() {
        let fileCount = this.getFilesCount();

        return fileCount > 0 ? this.GetName() : '';
    }

    /**
     *
     * @returns {string}
     */
    getNameInput() {
        let fileCount = this.getFilesCount();

        return fileCount <= 0 ? this.GetName() : '';
    }

    /**
     *
     * @returns {*}
     */
    getInputFile() {
        return this.refs.inputFile;
    }

    /**
     * @returns {integer}
     */
    getFilesCount() {
        let input = this.getInputFile();
        let response = 0;
        if (input) {
            response = input.files.length;
        }
        return response;
    }

    /**
     *
     */
    reRender() {
        let fileCount = this.getFilesCount();
        let fileName = '';

        if (fileCount > 0) {
            //with a file
            fileName = this.getInputFile().value;
        } else {
            fileName = this.GetValue();
        }

        this.setState({
            fileName: fileName
        });
    }

    render() {
        let name = this.props.name;
        let value = this.state.fileName;
        let field = this.props.field;
        let col = this.props.col;
        let counter = this.props.counter;

        return (
            <div className={`${col}`}>
                <div className={'file-field input-field'}>
                    <div className={'btn'}>
                        <span>{field.name} {counter()} {this.PrintHelp(field)}</span>
                        <input type="file" id={name} name={this.getNameFile()} ref="inputFile"
                               onChange={this.reRender.bind(this)}/>
                    </div>
                    <div className="file-path-wrapper input-field" style={{borderBottom: 'none'}}>
                        <input className="file-path validate" readOnly={true} type="text" value={value}/>
                        <input type="hidden" name={this.getNameInput()} value={this.GetValue()}/>
                    </div>
                </div>
            </div>
        );
    }
}

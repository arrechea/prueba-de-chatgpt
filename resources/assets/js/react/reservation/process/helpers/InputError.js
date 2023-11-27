import * as React from "react";
export default class InputError extends React.Component {

    render() {
        if (!this.props.text) {
            return null;
        }
        return (
            <div style={styles.text} {...this.props}>{this.props.text}</div>
        )
    }
}
const styles = ({
    text: {
        color: 'red',
    }
});

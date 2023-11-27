export default {
    /**
     * Parse errors sdk
     * @param errors
     * @returns {string}
     */
    parseError(errors){
        if (errors) {
            let text = '';
            Object.keys(errors).forEach((key) => {
                let mensajes = errors[key];
                if (Array.isArray(mensajes)) {
                    mensajes.forEach((mensaje) => {
                        text += `\n${mensaje}.`;
                    })
                }
            });
            return text;
        }
    },
}

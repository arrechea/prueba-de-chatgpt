export default {
   globalSalt: 'MeetingManager',
   cifrar(text){
       let Cypher = new this.Cipher(this.globalSalt);
       return Cypher(text);
   },
   descrifrar(text){
       let Decipher = new this.Decipher(this.globalSalt);
       return Decipher(text);
   },
   Cipher(salt) {
       let textToChars = text => text.split('').map(c => c.charCodeAt(0))
       let byteHex = n => ("0" + Number(n).toString(16)).substr(-2)
       let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code)
       return text => text.split('')
           .map(textToChars)
           .map(applySaltToChar)
           .map(byteHex)
           .join('')
   },
   Decipher(salt) {
       let textToChars = text => text.split('').map(c => c.charCodeAt(0));
       let saltChars = textToChars(salt);
       let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code);
       return encoded => encoded.match(/.{1,2}/g)
           .map(hex => parseInt(hex, 16))
           .map(applySaltToChar)
           .map(charCode => String.fromCharCode(charCode))
           .join('')
   },
}
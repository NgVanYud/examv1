import PizZip from 'pizzip';
// import PizZipUtils from 'pizzip/dist/pizzip-utils';
import JSZipUtils from 'jszip-utils';
import Docxtemplater from 'docxtemplater';
import { saveAs } from 'file-saver';

function loadFile(url, callback) {
  JSZipUtils.getBinaryContent(url, callback);
}

/**
 * Tao phieu diem bai thi cho sinh vien
 *
 * @param Object info
 */
export function generateResultDetail(result = {}, filePath = '/doc-templates/result-sheet.docx') {
  loadFile(filePath, function (error, content) {
    if (error) {
      throw error;
    }
    var zip = new PizZip(content);
    var doc = (new Docxtemplater()).loadZip(zip);
    doc.setData(result);
    // set the templateVariables
    // doc.setData({
    //   first_name: 'John',
    //   last_name: 'Doe',
    //   phone: '0652455478',
    //   description: 'New Website',
    // });
    try {
      // render the document (replace all occurences of {first_name} by John, {last_name} by Doe, ...)
      doc.render();
    } catch (error) {
      var e = {
        message: error.message,
        name: error.name,
        stack: error.stack,
        properties: error.properties,
      };
      console.log(JSON.stringify({
        error: e,
      }));
      // The error thrown here contains additional information when logged with JSON.stringify (it contains a property object).
      throw error;
    }
    var out = doc.getZip().generate({
      type: 'blob',
      mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    }); // Output the document using Data-URI
    saveAs(out, 'output.docx');
  });
}

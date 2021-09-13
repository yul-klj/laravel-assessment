import React, { useState, useEffect } from "react"
import ExportService from "../services/ExportService"
import Select from 'react-select'
import Alert from 'react-bootstrap/Alert'

const Export = (props) => {
  const [exportType, setExportType] = useState("CSV")
  const [exportField, setExportField] = useState("")
  const [showAlert, setShowAlert] = useState(false)

  const exportTypeChoice = [
    { value: 'CSV', label: 'CSV' },
    { value: 'XML', label: 'XML' }
  ]

  const exportColumnChoice = [
    { value: '', label: 'All' },
    { value: 'title', label: 'Title' },
    { value: 'author', label: 'Author' }
  ]

  const exportTypeSelected = (e) => {
    setExportType(e.value)
  }

  const exportFieldSelected = (e) => {
    setExportField(e.value)
  }

  const generateExport = () => {
    setShowAlert(true)
    ExportService.generate(exportType, exportField)
      .then(async (response) => {
        const resData = await response
        if (resData.status == 200) {
          return Promise.resolve(resData.data.content.data.id)
        }
      })
      .then(async (exportedId) => {
        const timer = setTimeout(() => {
          retrieveExport(exportedId)
        }, 1000)
      })
      .catch((e) => {
        console.log(e)
      })
  }

  const downloadByUrl = (url) => {
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('target', '_blank')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    setShowAlert(false)
  }

  const retrieveExport = (exportedId) => {
    ExportService.retrieve(exportedId)
    .then(async (response) => {
      const resData = await response
      if (resData.data.content.data.location) {
        downloadByUrl(resData.data.content.data.location)
      }
    })
    .catch((e) => {
      console.log(e)
    })
  }

  return (
    <div>
      {showAlert ?
        <Alert variant="success">
          Exporting, Kindly wait for the file to loaded on new tab
        </Alert>
      : ''}
      <h3>Export Feature</h3>
      <div className="input-group mb-3 w-50">
        <span htmlFor="type" className="input-group-text">Type</span>
        <Select
          className="form-control"
          id="type"
          options={exportTypeChoice}
          onChange={exportTypeSelected}
          defaultValue={{ value: 'CSV', label: 'CSV' }}
        />
      </div>

      <div className="input-group mb-3 w-50">
        <span htmlFor="field" className="input-group-text">Field</span>
        <Select
          className="form-control"
          id="field"
          options={exportColumnChoice}
          onChange={exportFieldSelected}
          defaultValue={{ value: '', label: 'All' }}
        />
      </div>

      <div className="w-50 text-end" role="group">
        <button className="btn btn-sm btn-success" onClick={generateExport}>
          Export CSV
        </button>
      </div>
    </div>
  )
}

export default Export
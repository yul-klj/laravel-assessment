import React, { Component } from "react"

const StructureLaravelValidationError = ({errorData}) => {
  const errorItems = Object.keys(errorData).map( (key, i) => {
    const error = errorData[key][0]
    return (
      <li>
        {error}
      </li>
    )
  })

  return (
    <ul>
      {errorItems}
    </ul>
  )
}

export default StructureLaravelValidationError
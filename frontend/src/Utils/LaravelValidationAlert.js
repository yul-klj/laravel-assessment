import Alert from 'react-bootstrap/Alert'
import StructureLaravelValidationError from "./StructureLaravelValidationError"

const LaravelValidationAlert = ({validationResError, setValidationResError}) => {
  return(
    <>
      {validationResError ?
        <Alert variant="warning" closeLabel="x" onClose={() => setValidationResError("")} dismissible>
          <Alert.Heading>Validation Error</Alert.Heading>
          <StructureLaravelValidationError errorData={validationResError}></StructureLaravelValidationError>
        </Alert>
      : ''}
    </>
  )
}

export default LaravelValidationAlert
<?php


namespace App\Libraries;

class ErrorHandler
{
    protected array $errorMap = [
        400 => ['type' => 'Bad Request', 'message' => 'Invalid request.'],
        401 => ['type' => 'Unauthorized', 'message' => 'Please log in.'],
        403 => ['type' => 'Forbidden', 'message' => 'Access denied.'],
        404 => ['type' => 'Not Found', 'message' => 'Resource not found.'],
        422 => ['type' => 'Validation Error', 'message' => 'Invalid input.'],
        500 => ['type' => 'Server Error', 'message' => 'Something went wrong.'],
    ];

    protected function build(int $code, ?string $customMessage = null): array
    {
        $error = $this->errorMap[$code] ?? [
            'type' => 'Unknown Error',
            'message' => 'Unexpected error.'
        ];

        return [
            'error_code' => $code,
            'error_type' => $error['type'],
            'error_message' => $customMessage ?? $error['message'],
            'title' => 'Travel Order | Error',
            'page'  => "Error {$code} - {$error['type']}"
        ];
    }

    public function badRequest($message = null)
    {
        return $this->build(400, $message);
    }

    public function unauthorized($message = null)
    {
        return $this->build(401, $message);
    }

    public function forbidden($message = null)
    {
        return $this->build(403, $message);
    }

    public function notFound($message = null)
    {
        return $this->build(404, $message);
    }

    public function validationError($errors = null)
    {
        if (is_array($errors)) {
            $errors = implode('<br>', $errors);
        }

        return $this->build(422, $errors);
    }
    public function serverError($message = null)
    {
        return $this->build(500, $message);
    }
}



// usage examples

// Error 400 - Bad Request

// if (!$this->request->getPost('id')) {
//     return $this->renderError(
//         $this->errorHandler->badRequest('Missing required parameter: id')
//     );
// }

// Error 401 - Unauthorized
// if (!$this->session->get('isLoggedIn')) {
//     return $this->renderError(
//         $this->errorHandler->unauthorized('Please log in first.')
//     );
// }

// Error 403 - Forbidden
// if (!$isAdmin) {
//     return $this->renderError(
//         $this->errorHandler->forbidden('Admins only.')
//     );
// }

// Error 404 - Not Found
// $user = $this->userModel->find($id);

// if (!$user) {
//     return $this->renderError(
//         $this->errorHandler->notFound('User not found.')
//     );
// }

//  Error 422 - Validation Error
// if (!$this->validate([
//     'email' => 'required|valid_email'
// ])) {
//     return $this->renderError(
//         $this->errorHandler->validationError($this->validator->getErrors())
//     );
// }

// Error 500 - Server Error
// try {
//     $this->userModel->save($data);
// } catch (\Exception $e) {
//     return $this->renderError(
//         $this->errorHandler->serverError('Failed to save user. Please try again.')
//     );
// }
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Show all authors
     * - - -
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAuthors() {
        return $this->success('List of authors.');
    }

    /**
     * Find an author
     * - - -
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function findAuthor($id) {
        return $this->success('Show an author.');
    }

    /**
     * Create an author
     * - - -
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAuthor(Request $request) {
        return $this->success('Create an author.');
    }

    /**
     * Update an author
     * - - -
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthor(Request $request, $id) {
        return $this->success('Update an author.');
    }

    /**
     * Delete an author
     * - - -
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAuthor($id) {
        return $this->success('Delete an author.');
    }

}

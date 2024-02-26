<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'nullable',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $event = Event::create($validatedData);

            return response()->json($event, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(Request $request, $id)
    {
        //
    }



}

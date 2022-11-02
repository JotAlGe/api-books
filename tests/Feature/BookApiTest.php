<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @test
     */

    public function can_get_all_books(): void
    {
        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ]);
    }

    /**
     * @test
     *
     */
    public function can_get_a_book(): void
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /**
     * @test
     *
     */
    public function can_create_a_book(): void
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My new book'
        ])
            ->assertJsonFragment([
                'title' => 'My new book'
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My new book'
        ]);
    }
    /**
     * @test
     *
     */
    public function can_update_a_book(): void
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Titulo actualizado'
        ])->assertJsonFragment([
            'title' => 'Titulo actualizado'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Titulo actualizado'
        ]);
    }

    /**
     * @test
     *
     */
    public function can_delete_book(): void
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}

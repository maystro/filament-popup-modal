<?php

namespace Maystro\FilamentPopupModal\Tests;

use Maystro\FilamentPopupModal\PopupModal;
use Maystro\FilamentPopupModal\Enums\Colors;
use PHPUnit\Framework\TestCase as BaseTestCase;

class SimplePopupModalTest extends BaseTestCase
{
    /** @test */
    public function it_can_create_a_basic_modal()
    {
        $modal = PopupModal::make();
        
        $this->assertInstanceOf(PopupModal::class, $modal);
        $this->assertIsString($modal->getData()['id']);
    }

    /** @test */
    public function it_can_set_title_and_body()
    {
        $modal = PopupModal::make()
            ->title('Test Title')
            ->body('Test Body');

        $data = $modal->getData();
        
        $this->assertEquals('Test Title', $data['heading']);
        $this->assertEquals('Test Body', $data['description']);
    }

    /** @test */
    public function it_can_set_color_with_string()
    {
        $modal = PopupModal::make()->color('success');
        
        $this->assertEquals('success', $modal->getData()['color']);
    }

    /** @test */
    public function it_can_set_color_with_enum()
    {
        $modal = PopupModal::make()->color(Colors::Success);
        
        $this->assertEquals('success', $modal->getData()['color']);
    }

    /** @test */
    public function it_can_set_progress()
    {
        $modal = PopupModal::make()->progress(50);
        
        $data = $modal->getData();
        $this->assertEquals(50, $data['progress']);
        $this->assertTrue($data['show-progress']);
    }

    /** @test */
    public function it_can_create_progress_modal()
    {
        $modal = PopupModal::progressModal('Loading...', 'Please wait...');
        
        $data = $modal->getData();
        $this->assertEquals('Loading...', $data['heading']);
        $this->assertEquals('Please wait...', $data['description']);
        $this->assertTrue($data['show-progress']);
        $this->assertEquals(0, $data['progress']);
    }

    /** @test */
    public function it_validates_progress_percentage()
    {
        $modal = PopupModal::make();
        
        // Test clamping to 0-100 range
        $modal->progress(150);
        $this->assertEquals(100, $modal->getData()['progress']);
        
        $modal->progress(-10);
        $this->assertEquals(0, $modal->getData()['progress']);
    }

    /** @test */
    public function it_can_set_icon_size()
    {
        $modal = PopupModal::make()->iconSize('lg');
        
        $this->assertEquals('lg', $modal->getData()['icon-size']);
    }

    /** @test */
    public function it_validates_icon_size()
    {
        $modal = PopupModal::make()->iconSize('invalid');
        
        // Should default to 'md' for invalid sizes
        $this->assertEquals('md', $modal->getData()['icon-size']);
    }

    /** @test */
    public function it_can_set_width()
    {
        $modal = PopupModal::make()->width('xl');
        
        $this->assertEquals('xl', $modal->getData()['width']);
    }

    /** @test */
    public function it_validates_width()
    {
        $modal = PopupModal::make()->width('invalid');
        
        // Should default to 'lg' for invalid widths
        $this->assertEquals('lg', $modal->getData()['width']);
    }

    /** @test */
    public function it_can_set_confirm_options()
    {
        $modal = PopupModal::make()
            ->confirm(true)
            ->confirmLabel('Yes')
            ->closeLabel('No');
        
        $data = $modal->getData();
        $this->assertTrue($data['has-confirm']);
        $this->assertEquals('Yes', $data['confirm-label']);
        $this->assertEquals('No', $data['close-label']);
    }

    /** @test */
    public function it_can_set_timeout()
    {
        $modal = PopupModal::make()->timeout(5000);
        
        $this->assertEquals(5000, $modal->getData()['timeout']);
    }
}

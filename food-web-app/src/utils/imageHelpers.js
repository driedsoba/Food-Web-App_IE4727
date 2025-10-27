/**
 * Helper function to get the correct image URL
 * Handles both external URLs and local file paths
 */
export const getImageUrl = (imageUrl) => {
  if (!imageUrl) {
    // Return a placeholder if no image provided
    return '/images/placeholder.jpg';
  }

  // If it's already a full URL (starts with http:// or https://), return as-is
  if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
    return imageUrl;
  }

  // If it's a local path, ensure it starts with /
  return imageUrl.startsWith('/') ? imageUrl : `/${imageUrl}`;
};

/**
 * Fallback image handler for broken images
 */
export const handleImageError = (e) => {
  e.target.src = '/images/placeholder.jpg';
  e.target.onerror = null; // Prevent infinite loop if placeholder also fails
};
